<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Tenants;
use App\Models\TenantsRentInformation;
use App\Models\Locations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TenantsController extends Controller
{
    /**
     * Display the Tenants listing with optional location filtering.
     */
    public function TenantPage(Request $request)
    {
        $locationId = $request->get('location_id');

        $query = Tenants::with([
            'location',
            'rentInformation',
            'billingsRent',
            'billingsElectricity',
            'billingsWater',
            'payments'
        ]);
        if ($locationId) {
            $query->where('location_id', $locationId);
        }

        $tenants = $query->latest()->get();
        $locations = Locations::all();
        $selectedLocation = $locationId ? Locations::find($locationId) : null;

        $allMonths = [
            'January', 'February', 'March', 'April',
            'May', 'June', 'July', 'August',
            'September', 'October', 'November', 'December'
        ];

        $tenants->transform(function ($tenant) use ($allMonths) {
            $rentBills = $tenant->billingsRent;
            $elecBills = $tenant->billingsElectricity;
            $waterBills = $tenant->billingsWater;
            $payments = $tenant->payments;

            $ledger = [];
            $runningRentBal = 0;
            $runningElecBal = 0;
            $runningWaterBal = 0;

            foreach ($allMonths as $monthName) {
                $r = $rentBills->first(function ($b) use ($monthName) {
                    return strcasecmp(trim($b->billing_month), trim($monthName)) === 0;
                });

                $e = $elecBills->first(function ($b) use ($monthName) {
                    return strcasecmp(trim($b->billing_month), trim($monthName)) === 0;
                });

                $w = $waterBills->first(function ($b) use ($monthName) {
                    return strcasecmp(trim($b->billing_month), trim($monthName)) === 0;
                });

                $monthApprovedPayments = $payments->filter(function ($p) use ($monthName) {
                    return strcasecmp(trim($p->billing_month), trim($monthName)) === 0
                        && in_array($p->status, ['Approved', 'Accepted']);
                });

                $mRentPaid  = $monthApprovedPayments->filter(fn($p) => strcasecmp(trim($p->payment_type ?? ''), 'Rent') === 0)->sum('amount');
                $mElecPaid  = $monthApprovedPayments->filter(fn($p) => strcasecmp(trim($p->payment_type ?? ''), 'Electricity') === 0)->sum('amount');
                $mWaterPaid = $monthApprovedPayments->filter(fn($p) => strcasecmp(trim($p->payment_type ?? ''), 'Water') === 0)->sum('amount');
                $mOtherPaid = $monthApprovedPayments->filter(fn($p) => !in_array(strtolower(trim($p->payment_type ?? '')), ['rent', 'electricity', 'water']))->sum('amount');

                $isRentPending = $r && strcasecmp($r->status ?? '', 'Pending') === 0;
                $isElecPending = $e && strcasecmp($e->status ?? '', 'Pending') === 0;
                $isWaterPending = $w && strcasecmp($w->status ?? '', 'Pending') === 0;

                $rentAmount = (float) ($r->rent_amount ?? 0);
                $elecAmount = $isElecPending ? 0 : (float) ($e->rent_amount ?? 0);
                $waterAmount = $isWaterPending ? 0 : (float) ($w->rent_amount ?? 0);

                $mTotalPaid = (float) $monthApprovedPayments->sum('amount');
                $hasBilling = ($r !== null || $e !== null || $w !== null || $mTotalPaid > 0);

                if (!$hasBilling) {
                    continue;
                }

                $prevRentBal  = $runningRentBal;
                $prevElecBal  = $runningElecBal;
                $prevWaterBal = $runningWaterBal;
                $previousTotalBalance = $prevRentBal + $prevElecBal + $prevWaterBal;

                $dueRent  = $prevRentBal + $rentAmount;
                $dueElec  = $prevElecBal + $elecAmount;
                $dueWater = $prevWaterBal + $waterAmount;

                $newRentBal  = max(0, $dueRent - $mRentPaid);
                $newElecBal  = max(0, $dueElec - $mElecPaid);
                $newWaterBal = max(0, $dueWater - $mWaterPaid);

                if ($mOtherPaid > 0) {
                    if ($newRentBal > 0) {
                        $deduct = min($newRentBal, $mOtherPaid);
                        $newRentBal -= $deduct;
                        $mOtherPaid -= $deduct;
                    }
                    if ($mOtherPaid > 0 && $newElecBal > 0) {
                        $deduct = min($newElecBal, $mOtherPaid);
                        $newElecBal -= $deduct;
                        $mOtherPaid -= $deduct;
                    }
                    if ($mOtherPaid > 0 && $newWaterBal > 0) {
                        $deduct = min($newWaterBal, $mOtherPaid);
                        $newWaterBal -= $deduct;
                        $mOtherPaid -= $deduct;
                    }
                }

                $runningRentBal  = $newRentBal;
                $runningElecBal  = $newElecBal;
                $runningWaterBal = $newWaterBal;

                $totalBilled = $rentAmount + $elecAmount + $waterAmount;
                $runningTotalBalance = $runningRentBal + $runningElecBal + $runningWaterBal;

                if ($runningTotalBalance <= 0 && $totalBilled > 0) {
                    $status = 'Paid';
                    $statusClass = 'success';
                } elseif ($mTotalPaid > 0 && $runningTotalBalance > 0) {
                    $status = 'Partial';
                    $statusClass = 'warning';
                } elseif ($totalBilled > 0 && $runningTotalBalance > 0) {
                    $status = 'Unpaid';
                    $statusClass = 'danger';
                } else {
                    $status = 'Paid';
                    $statusClass = 'success';
                }

                $ledger[] = [
                    'month'              => $monthName,
                    'rent_amount'        => $rentAmount,
                    'elec_amount'        => $elecAmount,
                    'water_amount'       => $waterAmount,
                    'total_billed'       => $totalBilled,
                    'total_paid'         => $mTotalPaid,
                    'previous_balance'   => $previousTotalBalance,
                    'prev_rent_bal'      => $prevRentBal,
                    'prev_elec_bal'      => $prevElecBal,
                    'prev_water_bal'     => $prevWaterBal,
                    'cumulative_balance' => $runningTotalBalance,
                    'cum_rent_bal'       => $runningRentBal,
                    'cum_elec_bal'       => $runningElecBal,
                    'cum_water_bal'      => $runningWaterBal,
                    'status'             => $status,
                    'status_class'       => $statusClass,
                ];
            }

            $tenant->ledger_data = $ledger;
            $tenant->total_outstanding_balance = $runningRentBal + $runningElecBal + $runningWaterBal;
            return $tenant;
        });

        return view('admins.locations.tenants.index', compact('tenants', 'locations', 'selectedLocation', 'locationId'));
    }

    /**
     * Store a newly created Tenant and their Rent Information.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname'       => 'required|string|max:255',
            'phone_number'   => 'required|string|max:20|unique:tenants,phone_number',
            'password'       => 'required|string|min:6',
            'location_id'    => 'required|exists:locations,id',
            'room'           => 'required|string|max:255',
            'monthly_rental' => 'required|numeric|min:0',
            'start_date'     => 'required|date',
        ]);

        DB::transaction(function () use ($validated) {
            $tenant = Tenants::create([
                'fullname'     => $validated['fullname'],
                'phone_number' => $validated['phone_number'],
                'password'     => Hash::make($validated['password']),
                'location_id'  => $validated['location_id'],
            ]);

            TenantsRentInformation::create([
                'tenant_id'      => $tenant->id,
                'room'           => $validated['room'],
                'monthly_rental' => $validated['monthly_rental'],
                'start_date'     => $validated['start_date'],
            ]);
        });

        return redirect()->back()->with('success', 'Tenant and rent information created successfully!');
    }
}

