<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TenantBillingsRent;
use App\Models\TenantBillingsElectricity;
use App\Models\TenantBillingsWater;
use App\Models\Locations;
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
            'January', 'February', 'March', 'April',
            'May', 'June', 'July', 'August',
            'September', 'October', 'November', 'December'
        ];

        // 1. Fetch Rent Billings
        $rentQuery = TenantBillingsRent::with(['tenant.location', 'tenant.rentInformation']);
        if ($selectedLocationId) {
            $rentQuery->whereHas('tenant', function ($q) use ($selectedLocationId) {
                $q->where('location_id', $selectedLocationId);
            });
        }
        if ($selectedYear) {
            $rentQuery->where(function ($q) use ($selectedYear) {
                $q->whereYear('due_date', $selectedYear)->orWhereYear('created_at', $selectedYear);
            });
        }
        $rentBillings = $rentQuery->get();

        // 2. Fetch Electricity Billings
        $elecQuery = TenantBillingsElectricity::with(['tenant.location', 'tenant.rentInformation']);
        if ($selectedLocationId) {
            $elecQuery->whereHas('tenant', function ($q) use ($selectedLocationId) {
                $q->where('location_id', $selectedLocationId);
            });
        }
        if ($selectedYear) {
            $elecQuery->where(function ($q) use ($selectedYear) {
                $q->whereYear('due_date', $selectedYear)->orWhereYear('created_at', $selectedYear);
            });
        }
        $elecBillings = $elecQuery->get();

        // 3. Fetch Water Billings
        $waterQuery = TenantBillingsWater::with(['tenant.location', 'tenant.rentInformation']);
        if ($selectedLocationId) {
            $waterQuery->whereHas('tenant', function ($q) use ($selectedLocationId) {
                $q->where('location_id', $selectedLocationId);
            });
        }
        if ($selectedYear) {
            $waterQuery->where(function ($q) use ($selectedYear) {
                $q->whereYear('due_date', $selectedYear)->orWhereYear('created_at', $selectedYear);
            });
        }
        $waterBillings = $waterQuery->get();

        // Aggregate statistics per month combined across Rent, Electricity, and Water
        $monthsData = collect($allMonths)->map(function ($monthName) use ($rentBillings, $elecBillings, $waterBillings) {
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

            $combinedBillings = $tenantIds->map(function ($tenantId) use ($monthRents, $monthElecs, $monthWaters, $monthName) {
                $rent = $monthRents->firstWhere('tenant_id', $tenantId);
                $elec = $monthElecs->firstWhere('tenant_id', $tenantId);
                $water = $monthWaters->firstWhere('tenant_id', $tenantId);

                $tenantObj = $rent->tenant ?? $elec->tenant ?? $water->tenant ?? null;

                $rentAmount = (float) ($rent->rent_amount ?? 0);
                $rentBalance = (float) ($rent->balance ?? 0);
                $rentStatus = $rent->status ?? 'Unpaid';

                $elecAmount = (float) ($elec->rent_amount ?? 0);
                $elecBalance = (float) ($elec->balance ?? 0);
                $elecStatus = $elec->status ?? 'Unpaid';

                $waterAmount = (float) ($water->rent_amount ?? 0);
                $waterBalance = (float) ($water->balance ?? 0);
                $waterStatus = $water->status ?? 'Unpaid';

                $totalAmount = $rentAmount + $elecAmount + $waterAmount;
                $totalBalance = $rentBalance + $elecBalance + $waterBalance;

                if ($totalBalance <= 0) {
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
                    'has_rent'         => ($rent !== null && $rentAmount > 0),
                    'has_elec'         => ($elec !== null && $elecAmount > 0),
                    'has_water'        => ($water !== null && $waterAmount > 0),
                    'total_amount'     => $totalAmount,
                    'total_balance'    => $totalBalance,
                    'status'           => $status,
                    'due_date'         => $rent->due_date ?? $elec->due_date ?? $water->due_date ?? null,
                    'proof_of_billing' => $rent->proof_of_billing ?? $elec->proof_of_billing ?? $water->proof_of_billing ?? null,
                    'elec_proof'       => $elec->proof_of_billing ?? null,
                    'water_proof'      => $water->proof_of_billing ?? null,
                ];
            });

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

        TenantBillingsRent::create([
            'tenant_id'        => $request->tenant_id,
            'billing_month'    => $request->billing_month,
            'due_date'         => $request->due_date,
            'rent_amount'      => $request->rent_amount,
            'balance'          => $balance,
            'proof_of_billing' => $proofPath,
            'status'           => $request->status,
        ]);

        return redirect()->back()->with('success', 'Rent billing added successfully!');
    }
}

