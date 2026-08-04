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
        $paymentsQuery = TenantPayments::with(['receiver', 'billingRent', 'billingElectricity', 'billingWater'])
            ->where('tenant_id', $tenant->id);

        if ($selectedYear) {
            $paymentsQuery->where(function ($q) use ($selectedYear) {
                $q->whereYear('created_at', $selectedYear);
            });
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

        return view('tenants.payments.index', compact(
            'tenant',
            'monthsData',
            'selectedYear',
            'availableYears',
            'allMonths'
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
        $amount = (float) $request->amount;
        $type = $request->type;
        $getFullname = ($type === 'CASH') ? $request->get_fullname : null;

        $elecAmount = ($category === 'Electricity') ? ((float) ($request->electricity_amount ?? $amount)) : null;
        $waterAmount = ($category === 'Water') ? ((float) ($request->water_amount ?? $amount)) : null;

        $rentBillingId = null;
        $elecBillingId = null;
        $waterBillingId = null;

        if ($category === 'Monthly Rental') {
            $paymentType = 'Rent';
            // Find existing rent billing or create
            $rentBilling = TenantBillingsRent::firstOrCreate(
                [
                    'tenant_id'     => $tenant->id,
                    'billing_month' => $billingMonth,
                ],
                [
                    'due_date'    => date('Y-m-15'),
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
                ->first();

            if ($elecBilling) {
                if ($proofOfBillingPath) {
                    $elecBilling->update(['proof_of_billing' => $proofOfBillingPath]);
                }
            } else {
                $elecBilling = TenantBillingsElectricity::create([
                    'tenant_id'        => $tenant->id,
                    'billing_month'    => $billingMonth,
                    'due_date'         => date('Y-m-20'),
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
                ->first();

            if ($waterBilling) {
                if ($proofOfBillingPath) {
                    $waterBilling->update(['proof_of_billing' => $proofOfBillingPath]);
                }
            } else {
                $waterBilling = TenantBillingsWater::create([
                    'tenant_id'        => $tenant->id,
                    'billing_month'    => $billingMonth,
                    'due_date'         => date('Y-m-25'),
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
            'file_electricity'               => ($category === 'Electricity') ? $proofOfBillingPath : null,
            'file_water'                     => ($category === 'Water') ? $proofOfBillingPath : null,
            'electricity_amount'             => $elecAmount,
            'water_amount'                   => $waterAmount,
            'billing_month'                  => $billingMonth,
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
