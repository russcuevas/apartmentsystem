<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\TenantPayments;
use App\Models\TenantBillingsRent;
use App\Models\TenantBillingsElectricity;
use App\Models\TenantBillingsWater;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantPaymentsController extends Controller
{
    /**
     * Display Tenant Payments Overview (UI datatable by month & year).
     */
    public function index(Request $request)
    {
        $tenant = Auth::guard('tenant')->user();
        if (!$tenant) {
            return redirect()->route('tenant.login.page');
        }

        $currentYear = (int) date('Y');
        $selectedYear = $request->get('year', (string) $currentYear);
        $availableYears = array_map('strval', range($currentYear - 2, $currentYear + 5));

        $allMonths = [
            'January', 'February', 'March', 'April',
            'May', 'June', 'July', 'August',
            'September', 'October', 'November', 'December'
        ];

        // Fetch tenant's payment records for the selected year
        $paymentsQuery = TenantPayments::with([
            'tenant.location',
            'tenant.rentInformation',
            'receiver',
            'billingRent',
            'billingElectricity',
            'billingWater'
        ])->where('tenant_id', $tenant->id);

        if ($selectedYear) {
            $paymentsQuery->where('billing_year', (int) $selectedYear);
        }

        $allPayments = $paymentsQuery->orderBy('created_at', 'desc')->get();

        // Aggregate statistics per month (January - December)
        $monthsData = collect($allMonths)->map(function ($monthName) use ($allPayments) {
            $monthPayments = $allPayments->filter(function ($payment) use ($monthName) {
                return strcasecmp(trim($payment->billing_month), trim($monthName)) === 0;
            })->values();

            $totalCount = $monthPayments->count();
            $totalAmount = $monthPayments->sum('amount');

            $acceptedCount = $monthPayments->where('status', 'Accepted')->count();
            $pendingCount = $monthPayments->where('status', 'Pending')->count();
            $declinedCount = $monthPayments->where('status', 'Declined')->count();

            if ($totalCount === 0) {
                $status = 'No Payments';
                $statusClass = 'neutral';
            } elseif ($declinedCount > 0 && $acceptedCount === 0 && $pendingCount === 0) {
                $status = 'Declined';
                $statusClass = 'danger';
            } elseif ($pendingCount > 0) {
                $status = 'Pending';
                $statusClass = 'warning';
            } else {
                $status = 'Accepted';
                $statusClass = 'success';
            }

            return [
                'month'          => $monthName,
                'total_count'    => $totalCount,
                'total_amount'   => $totalAmount,
                'status'         => $status,
                'status_class'   => $statusClass,
                'payments'       => $monthPayments,
            ];
        });

        // Fetch existing billings across all years to calculate remaining balance per category per month and year
        $allRentBills = TenantBillingsRent::where('tenant_id', $tenant->id)->get();
        $allElecBills = TenantBillingsElectricity::where('tenant_id', $tenant->id)->get();
        $allWaterBills = TenantBillingsWater::where('tenant_id', $tenant->id)->get();
        $allTenantPayments = TenantPayments::where('tenant_id', $tenant->id)->get();

        $tenantBillingsSummary = [];

        foreach ($availableYears as $y) {
            $tenantBillingsSummary[$y] = [];

            foreach ($allMonths as $m) {
                $r = $allRentBills->first(function ($b) use ($m, $y) {
                    return strcasecmp(trim($b->billing_month), trim($m)) === 0 && (int) $b->billing_year === (int) $y;
                });

                $e = $allElecBills->first(function ($b) use ($m, $y) {
                    return strcasecmp(trim($b->billing_month), trim($m)) === 0 && (int) $b->billing_year === (int) $y;
                });

                $w = $allWaterBills->first(function ($b) use ($m, $y) {
                    return strcasecmp(trim($b->billing_month), trim($m)) === 0 && (int) $b->billing_year === (int) $y;
                });

                $mApprovedPayments = $allTenantPayments->filter(function ($p) use ($m, $y) {
                    return strcasecmp(trim($p->billing_month), trim($m)) === 0
                        && (int) $p->billing_year === (int) $y
                        && in_array($p->status, ['Approved', 'Accepted', 'Pending']);
                });

                $baseRent = (float) ($r ? $r->rent_amount : 0);
                $rentPaid = (float) $mApprovedPayments->filter(fn($p) => strcasecmp(trim($p->payment_type ?? ''), 'Rent') === 0)->sum('amount');
                $rentBal  = max(0, $baseRent - $rentPaid);

                $elecAmount = (float) ($e->rent_amount ?? 0);
                $elecPaid   = (float) $mApprovedPayments->filter(fn($p) => strcasecmp(trim($p->payment_type ?? ''), 'Electricity') === 0)->sum('amount');
                $elecBal    = max(0, $elecAmount - $elecPaid);

                $waterAmount = (float) ($w->rent_amount ?? 0);
                $waterPaid   = (float) $mApprovedPayments->filter(fn($p) => strcasecmp(trim($p->payment_type ?? ''), 'Water') === 0)->sum('amount');
                $waterBal    = max(0, $waterAmount - $waterPaid);

                $tenantBillingsSummary[$y][$m] = [
                    'Monthly Rental' => [
                        'total_amount'      => $baseRent,
                        'paid_amount'       => $rentPaid,
                        'balance'           => $rentBal,
                        'has_existing_bill' => ($r !== null),
                    ],
                    'Electricity' => [
                        'total_amount'      => $elecAmount,
                        'paid_amount'       => $elecPaid,
                        'balance'           => $elecBal,
                        'has_existing_bill' => ($e !== null),
                        'proof_of_billing'  => $e ? $e->proof_of_billing : null,
                    ],
                    'Water' => [
                        'total_amount'      => $waterAmount,
                        'paid_amount'       => $waterPaid,
                        'balance'           => $waterBal,
                        'has_existing_bill' => ($w !== null),
                        'proof_of_billing'  => $w ? $w->proof_of_billing : null,
                    ]
                ];
            }
        }

        return view('tenants.payments.index', compact(
            'tenant',
            'monthsData',
            'selectedYear',
            'availableYears',
            'allMonths',
            'tenantBillingsSummary'
        ));
    }

    /**
     * Store a new payment submitted by the tenant.
     */
    public function store(Request $request)
    {
        $tenant = Auth::guard('tenant')->user();
        if (!$tenant) {
            return redirect()->route('tenant.login.page');
        }

        $request->validate([
            'category'           => 'required|in:Monthly Rental,Electricity,Water',
            'billing_month'      => 'required|string',
            'billing_year'       => 'nullable|integer|min:2000|max:2100',
            'amount'             => 'required|numeric|min:0.01',
            'type'               => 'required|in:CASH,ECASH',
            'get_fullname'       => 'nullable|string|max:255',
            'electricity_amount' => 'nullable|numeric|min:0',
            'water_amount'       => 'nullable|numeric|min:0',
            'payment_proof'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5048',
            'proof_of_billing'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5048',
        ]);

        // 1. Handle payment_proof upload
        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_pay_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/payments'), $filename);
            $paymentProofPath = asset('uploads/payments/' . $filename);
        }

        // 2. Handle proof_of_billing upload (for Electricity or Water)
        $proofOfBillingPath = null;
        if ($request->hasFile('proof_of_billing')) {
            $file = $request->file('proof_of_billing');
            $filename = time() . '_bill_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/proofs'), $filename);
            $proofOfBillingPath = asset('uploads/proofs/' . $filename);
        }

        $category = $request->category;
        $billingMonth = $request->billing_month;
        $billingYear = (int) ($request->billing_year ?? date('Y'));
        $amount = (float) $request->amount;
        $type = $request->type;
        $getFullname = ($type === 'CASH') ? $request->get_fullname : null;

        $elecAmount = ($category === 'Electricity') ? ((float) ($request->electricity_amount ?? $amount)) : null;
        $waterAmount = ($category === 'Water') ? ((float) ($request->water_amount ?? $amount)) : null;

        $rentBillingId = null;
        $elecBillingId = null;
        $waterBillingId = null;
        $paymentType = null;

        if ($category === 'Monthly Rental') {
            $paymentType = 'Rent';
            // Find existing rent billing or create
            $rentBilling = TenantBillingsRent::firstOrCreate(
                [
                    'tenant_id'     => $tenant->id,
                    'billing_month' => $billingMonth,
                    'billing_year'  => $billingYear,
                ],
                [
                    'due_date'    => date("{$billingYear}-m-15"),
                    'rent_amount' => $amount,
                    'balance'     => $amount,
                    'status'      => 'Unpaid',
                ]
            );
            $rentBillingId = $rentBilling->id;

        } elseif ($category === 'Electricity') {
            $paymentType = 'Electricity';
            // Find existing electricity billing or create
            $elecBilling = TenantBillingsElectricity::where('tenant_id', $tenant->id)
                ->where('billing_month', $billingMonth)
                ->where(function ($q) use ($billingYear) {
                    $q->where('billing_year', $billingYear)->orWhereNull('billing_year');
                })->first();

            if ($elecBilling) {
                $updates = [];
                if ($proofOfBillingPath) {
                    $updates['proof_of_billing'] = $proofOfBillingPath;
                }
                if (empty($elecBilling->billing_year)) {
                    $updates['billing_year'] = $billingYear;
                }
                if (!empty($updates)) {
                    $elecBilling->update($updates);
                }
            } else {
                $elecBilling = TenantBillingsElectricity::create([
                    'tenant_id'        => $tenant->id,
                    'billing_month'    => $billingMonth,
                    'billing_year'     => $billingYear,
                    'due_date'         => date("{$billingYear}-m-20"),
                    'rent_amount'      => $elecAmount ?? $amount,
                    'balance'          => $elecAmount ?? $amount,
                    'proof_of_billing' => $proofOfBillingPath,
                    'status'           => 'Unpaid',
                ]);
            }
            $elecBillingId = $elecBilling->id;

        } elseif ($category === 'Water') {
            $paymentType = 'Water';
            // Find existing water billing or create
            $waterBilling = TenantBillingsWater::where('tenant_id', $tenant->id)
                ->where('billing_month', $billingMonth)
                ->where(function ($q) use ($billingYear) {
                    $q->where('billing_year', $billingYear)->orWhereNull('billing_year');
                })->first();

            if ($waterBilling) {
                $updates = [];
                if ($proofOfBillingPath) {
                    $updates['proof_of_billing'] = $proofOfBillingPath;
                }
                if (empty($waterBilling->billing_year)) {
                    $updates['billing_year'] = $billingYear;
                }
                if (!empty($updates)) {
                    $waterBilling->update($updates);
                }
            } else {
                $waterBilling = TenantBillingsWater::create([
                    'tenant_id'        => $tenant->id,
                    'billing_month'    => $billingMonth,
                    'billing_year'     => $billingYear,
                    'due_date'         => date("{$billingYear}-m-25"),
                    'rent_amount'      => $waterAmount ?? $amount,
                    'balance'          => $waterAmount ?? $amount,
                    'proof_of_billing' => $proofOfBillingPath,
                    'status'           => 'Unpaid',
                ]);
            }
            $waterBillingId = $waterBilling->id;
        }

        // Create TenantPayments entry with pending status and nullable received_by
        TenantPayments::create([
            'tenant_id'                      => $tenant->id,
            'tenant_billings_rent_id'        => $rentBillingId,
            'tenant_billings_electricity_id' => $elecBillingId,
            'tenant_billings_water_id'       => $waterBillingId,
            'file_electricity'               => ($category === 'Electricity') ? ($proofOfBillingPath ?: ($elecBilling ? $elecBilling->proof_of_billing : null)) : null,
            'file_water'                     => ($category === 'Water') ? ($proofOfBillingPath ?: ($waterBilling ? $waterBilling->proof_of_billing : null)) : null,
            'electricity_amount'             => $elecAmount,
            'water_amount'                   => $waterAmount,
            'billing_month'                  => $billingMonth,
            'billing_year'                   => $billingYear,
            'amount'                         => $amount,
            'type'                           => $type,
            'get_fullname'                   => $getFullname,
            'payment_type'                   => $paymentType,
            'payment_proof'                  => $paymentProofPath,
            'status'                         => 'Pending',
            'received_by'                    => null,
        ]);

        return redirect()->back()->with('success', 'Payment submitted successfully! Status is set to Pending for admin verification.');
    }
}
