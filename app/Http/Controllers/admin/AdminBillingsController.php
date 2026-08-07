<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TenantBillingsRent;
use App\Models\TenantBillingsElectricity;
use App\Models\TenantBillingsWater;
use App\Models\Locations;
use App\Models\TenantPayments;
use App\Models\Tenants;
use Illuminate\Http\Request;

class AdminBillingsController extends Controller
{
    /**
     * Display Monthly Billings Overview with Year & Location filters.
     */
    public function index(Request $request)
    {
        $currentYear = (int) date('Y');
        $selectedYear = $request->get('year', (string) $currentYear);
        $selectedLocationId = $request->get('location_id');

        // Generate years from current year up to 10 years into the future
        $availableYears = array_map('strval', range($currentYear, $currentYear + 10));
        $locations = Locations::all();
        $selectedLocation = $selectedLocationId ? Locations::find($selectedLocationId) : null;

        $tenantsQuery = Tenants::with(['location', 'rentInformation']);
        if ($selectedLocationId) {
            $tenantsQuery->where('location_id', $selectedLocationId);
        }
        $allTenants = $tenantsQuery->get();

        // 12 calendar months list
        $allMonths = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        // 1. Fetch Rent Billings
        $rentQuery = TenantBillingsRent::with(['tenant.location', 'tenant.rentInformation', 'payments']);
        if ($selectedLocationId) {
            $rentQuery->whereHas('tenant', function ($q) use ($selectedLocationId) {
                $q->where('location_id', $selectedLocationId);
            });
        }
        if ($selectedYear) {
            $rentQuery->where('billing_year', (int) $selectedYear);
        }
        $rentBillings = $rentQuery->get();

        // 2. Fetch Electricity Billings
        $elecQuery = TenantBillingsElectricity::with(['tenant.location', 'tenant.rentInformation', 'payments']);
        if ($selectedLocationId) {
            $elecQuery->whereHas('tenant', function ($q) use ($selectedLocationId) {
                $q->where('location_id', $selectedLocationId);
            });
        }
        if ($selectedYear) {
            $elecQuery->where('billing_year', (int) $selectedYear);
        }
        $elecBillings = $elecQuery->get();

        // 3. Fetch Water Billings
        $waterQuery = TenantBillingsWater::with(['tenant.location', 'tenant.rentInformation', 'payments']);
        if ($selectedLocationId) {
            $waterQuery->whereHas('tenant', function ($q) use ($selectedLocationId) {
                $q->where('location_id', $selectedLocationId);
            });
        }
        if ($selectedYear) {
            $waterQuery->where('billing_year', (int) $selectedYear);
        }
        $waterBillings = $waterQuery->get();

        // 4. Fetch All Payments
        $allPayments = TenantPayments::with('receiver')->get();

        // Aggregate statistics per month combined across Rent, Electricity, and Water
        $monthsData = collect($allMonths)->map(function ($monthName) use ($rentBillings, $elecBillings, $waterBillings, $allPayments) {
            $monthRents = $rentBillings->filter(function ($b) use ($monthName) {
                return strcasecmp(trim($b->billing_month), trim($monthName)) === 0;
            })->values();

            $monthElecs = $elecBillings->filter(function ($b) use ($monthName) {
                return strcasecmp(trim($b->billing_month), trim($monthName)) === 0;
            })->values();

            $monthWaters = $waterBillings->filter(function ($b) use ($monthName) {
                return strcasecmp(trim($b->billing_month), trim($monthName)) === 0;
            })->values();

            // Get unique tenant IDs across all 3 tables for this month
            $tenantIds = $monthRents->pluck('tenant_id')
                ->merge($monthElecs->pluck('tenant_id'))
                ->merge($monthWaters->pluck('tenant_id'))
                ->unique()->values();

            $combinedBillings = $tenantIds->map(function ($tenantId) use ($monthRents, $monthElecs, $monthWaters, $monthName, $allPayments) {
                $rent = $monthRents->firstWhere('tenant_id', $tenantId);
                $elec = $monthElecs->firstWhere('tenant_id', $tenantId);
                $water = $monthWaters->firstWhere('tenant_id', $tenantId);

                $tenantPayments = $allPayments->filter(function ($p) use ($tenantId, $monthName) {
                    return $p->tenant_id == $tenantId && strcasecmp(trim($p->billing_month), trim($monthName)) === 0;
                })->values();

                $tenantObj = $rent->tenant ?? $elec->tenant ?? $water->tenant ?? null;

                $isRentPending = $rent && (
                    strcasecmp($rent->status ?? '', 'Pending') === 0 ||
                    ($rent->relationLoaded('payments') && $rent->payments->contains('status', 'Pending'))
                );
                $isElecPending = $elec && (
                    strcasecmp($elec->status ?? '', 'Pending') === 0 ||
                    ($elec->relationLoaded('payments') && $elec->payments->contains('status', 'Pending'))
                );
                $isWaterPending = $water && (
                    strcasecmp($water->status ?? '', 'Pending') === 0 ||
                    ($water->relationLoaded('payments') && $water->payments->contains('status', 'Pending'))
                );

                $approvedPayments = $tenantPayments->filter(function ($p) {
                    return in_array($p->status, ['Approved', 'Accepted']);
                });

                $rentPaid  = (float) $approvedPayments->filter(fn($p) => strcasecmp(trim($p->payment_type ?? ''), 'Rent') === 0)->sum('amount');
                $elecPaid  = (float) $approvedPayments->filter(fn($p) => strcasecmp(trim($p->payment_type ?? ''), 'Electricity') === 0)->sum('amount');
                $waterPaid = (float) $approvedPayments->filter(fn($p) => strcasecmp(trim($p->payment_type ?? ''), 'Water') === 0)->sum('amount');

                $rentAmount  = (float) ($rent->rent_amount ?? 0);
                $rentBalance = max(0, $rentAmount - $rentPaid);
                $rentStatus  = $isRentPending ? 'Pending' : ($rentAmount > 0 ? ($rentBalance <= 0 ? 'Paid' : ($rentPaid > 0 ? 'Partial' : 'Unpaid')) : 'Unpaid');

                $elecAmount  = $isElecPending ? 0 : (float) ($elec->rent_amount ?? 0);
                $elecBalance = $isElecPending ? 0 : max(0, $elecAmount - $elecPaid);
                $elecStatus  = $isElecPending ? 'Pending' : ($elecAmount > 0 ? ($elecBalance <= 0 ? 'Paid' : ($elecPaid > 0 ? 'Partial' : 'Unpaid')) : 'Unpaid');

                $waterAmount  = $isWaterPending ? 0 : (float) ($water->rent_amount ?? 0);
                $waterBalance = $isWaterPending ? 0 : max(0, $waterAmount - $waterPaid);
                $waterStatus  = $isWaterPending ? 'Pending' : ($waterAmount > 0 ? ($waterBalance <= 0 ? 'Paid' : ($waterPaid > 0 ? 'Partial' : 'Unpaid')) : 'Unpaid');

                $hasRent = ($rent !== null && $rentAmount > 0);
                $hasElec = ($elec !== null && $elecAmount > 0 && !$isElecPending);
                $hasWater = ($water !== null && $waterAmount > 0 && !$isWaterPending);

                $totalAmount = $rentAmount + $elecAmount + $waterAmount;
                $totalBalance = $rentBalance + $elecBalance + $waterBalance;

                if ($totalAmount <= 0) {
                    $status = 'Pending';
                } elseif ($totalBalance <= 0) {
                    $status = 'Paid';
                } elseif ($totalBalance < $totalAmount) {
                    $status = 'Partial';
                } else {
                    $status = 'Unpaid';
                }

                return [
                    'tenant_id'        => $tenantId,
                    'tenant'           => $tenantObj,
                    'billing_month'    => $monthName,
                    'rent_amount'      => $rentAmount,
                    'rent_balance'     => $rentBalance,
                    'rent_status'      => $rentStatus,
                    'elec_amount'      => $elecAmount,
                    'elec_balance'     => $elecBalance,
                    'elec_status'      => $elecStatus,
                    'water_amount'     => $waterAmount,
                    'water_balance'    => $waterBalance,
                    'water_status'     => $waterStatus,
                    'has_rent'         => $hasRent,
                    'has_elec'         => $hasElec,
                    'has_water'        => $hasWater,
                    'total_amount'     => $totalAmount,
                    'total_balance'    => $totalBalance,
                    'status'           => $status,
                    'due_date'         => $rent->due_date ?? $elec->due_date ?? $water->due_date ?? null,
                    'proof_of_billing' => $rent->proof_of_billing ?? $elec->proof_of_billing ?? $water->proof_of_billing ?? null,
                    'elec_proof'       => $isElecPending ? null : ($elec->proof_of_billing ?? null),
                    'water_proof'      => $isWaterPending ? null : ($water->proof_of_billing ?? null),
                    'payments'         => $tenantPayments,
                ];
            });

            // Filter out tenants who have no active/non-pending billings in this month
            $combinedBillings = $combinedBillings->filter(function ($b) {
                return $b['has_rent'] || $b['has_elec'] || $b['has_water'];
            })->values();

            $totalTenants = $combinedBillings->count();
            $totalAmount = $combinedBillings->sum('total_amount');
            $totalBalance = $combinedBillings->sum('total_balance');
            $paidCount = $combinedBillings->where('status', 'Paid')->count();
            $unpaidCount = $combinedBillings->where('status', '!=', 'Paid')->count();

            if ($totalTenants === 0) {
                $status = 'No Billings';
                $statusClass = 'neutral';
            } elseif ($unpaidCount === 0) {
                $status = 'Completed';
                $statusClass = 'success';
            } elseif ($paidCount > 0) {
                $status = 'Partial';
                $statusClass = 'warning';
            } else {
                $status = 'Pending';
                $statusClass = 'danger';
            }

            return [
                'month'         => $monthName,
                'total_tenants' => $totalTenants,
                'total_amount'  => $totalAmount,
                'total_balance' => $totalBalance,
                'status'        => $status,
                'status_class'  => $statusClass,
                'billings'      => $combinedBillings,
            ];
        });

        return view('admins.locations.billings.index', compact(
            'monthsData',
            'selectedYear',
            'availableYears',
            'locations',
            'selectedLocationId',
            'selectedLocation',
            'allTenants',
            'allMonths'
        ));
    }

    /**
     * Store a new tenant rent billing.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tenant_id'        => 'required|exists:tenants,id',
            'billing_month'    => 'required|string',
            'billing_year'     => 'nullable|integer|min:2000|max:2100',
            'due_date'         => 'required|date',
            'rent_amount'      => 'required|numeric|min:0',
            'proof_of_billing' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'status'           => 'required|in:Unpaid,Paid,Pending',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof_of_billing')) {
            $file = $request->file('proof_of_billing');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/proofs'), $filename);
            $proofPath = asset('uploads/proofs/' . $filename);
        }

        $balance = ($request->status === 'Paid') ? 0.00 : (float) $request->rent_amount;
        $billingYear = (int) ($request->billing_year ?? date('Y', strtotime($request->due_date)));

        TenantBillingsRent::create([
            'tenant_id'        => $request->tenant_id,
            'billing_month'    => $request->billing_month,
            'billing_year'     => $billingYear,
            'due_date'         => $request->due_date,
            'rent_amount'      => $request->rent_amount,
            'balance'          => $balance,
            'proof_of_billing' => $proofPath,
            'status'           => $request->status,
        ]);

        return redirect()->back()->with('success', 'Rent billing added successfully!');
    }
}
