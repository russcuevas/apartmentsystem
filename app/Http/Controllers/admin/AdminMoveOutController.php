<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Tenants;
use App\Models\Locations;
use Illuminate\Http\Request;

class AdminMoveOutController extends Controller
{
    /**
     * Display the Moved Out Tenants directory.
     */
    public function index(Request $request)
    {
        $locationId = $request->get('location_id');

        $query = Tenants::with([
            'location',
            'rentInformation',
            'billingsRent',
            'billingsElectricity',
            'billingsWater',
            'payments.receiver'
        ])->whereHas('rentInformation', function ($q) {
            $q->where('move_out', true);
        });

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
            $documents = [];

            $runningRentBal = 0;
            $runningElecBal = 0;
            $runningWaterBal = 0;

            foreach ($allMonths as $monthName) {
                $r = $rentBills->first(fn($b) => strcasecmp(trim($b->billing_month), trim($monthName)) === 0);
                $e = $elecBills->first(fn($b) => strcasecmp(trim($b->billing_month), trim($monthName)) === 0);
                $w = $waterBills->first(fn($b) => strcasecmp(trim($b->billing_month), trim($monthName)) === 0);

                // Collect electricity proof document if available
                if ($e && !empty($e->proof_of_billing)) {
                    $documents[] = [
                        'type'        => 'Electricity Bill Document',
                        'category'    => 'Electricity',
                        'month'       => $monthName,
                        'year'        => $e->billing_year ?? date('Y', strtotime($e->created_at)),
                        'url'         => $e->proof_of_billing,
                        'date_added'  => $e->created_at ? $e->created_at->format('M d, Y') : 'N/A',
                    ];
                }

                // Collect water proof document if available
                if ($w && !empty($w->proof_of_billing)) {
                    $documents[] = [
                        'type'        => 'Water Bill Document',
                        'category'    => 'Water',
                        'month'       => $monthName,
                        'year'        => $w->billing_year ?? date('Y', strtotime($w->created_at)),
                        'url'         => $w->proof_of_billing,
                        'date_added'  => $w->created_at ? $w->created_at->format('M d, Y') : 'N/A',
                    ];
                }

                $monthApprovedPayments = $payments->filter(function ($p) use ($monthName) {
                    return strcasecmp(trim($p->billing_month), trim($monthName)) === 0
                        && in_array($p->status, ['Approved', 'Accepted']);
                });

                // Collect payment proof documents
                foreach ($payments->filter(fn($p) => strcasecmp(trim($p->billing_month), trim($monthName)) === 0) as $p) {
                    $proofUrl = $p->proof_of_payment ?? $p->proof_of_billing ?? null;
                    if (!empty($proofUrl)) {
                        $documents[] = [
                            'type'        => 'Payment Proof Receipt (' . ($p->payment_type ?? 'Payment') . ')',
                            'category'    => $p->payment_type ?? 'Payment',
                            'month'       => $monthName,
                            'year'        => $p->billing_year ?? date('Y', strtotime($p->created_at)),
                            'url'         => $proofUrl,
                            'amount'      => number_format($p->amount, 2),
                            'status'      => $p->status,
                            'date_added'  => $p->created_at ? $p->created_at->format('M d, Y') : 'N/A',
                        ];
                    }
                }

                $mRentPaid  = $monthApprovedPayments->filter(fn($p) => strcasecmp(trim($p->payment_type ?? ''), 'Rent') === 0)->sum('amount');
                $mElecPaid  = $monthApprovedPayments->filter(fn($p) => strcasecmp(trim($p->payment_type ?? ''), 'Electricity') === 0)->sum('amount');
                $mWaterPaid = $monthApprovedPayments->filter(fn($p) => strcasecmp(trim($p->payment_type ?? ''), 'Water') === 0)->sum('amount');
                $mOtherPaid = $monthApprovedPayments->filter(fn($p) => !in_array(strtolower(trim($p->payment_type ?? '')), ['rent', 'electricity', 'water']))->sum('amount');

                $isRentPending  = $r && strcasecmp($r->status ?? '', 'Pending') === 0;
                $isElecPending  = $e && strcasecmp($e->status ?? '', 'Pending') === 0;
                $isWaterPending = $w && strcasecmp($w->status ?? '', 'Pending') === 0;

                $rentAmount  = (float) ($r->rent_amount ?? 0);
                $elecAmount  = $isElecPending ? 0 : (float) ($e->rent_amount ?? 0);
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

                $bYear = $r->billing_year ?? $e->billing_year ?? $w->billing_year ?? ($monthApprovedPayments->first()->billing_year ?? date('Y'));

                $ledger[] = [
                    'month'              => $monthName,
                    'year'               => $bYear,
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
            $tenant->documents_data = $documents;
            $tenant->total_outstanding_balance = $runningRentBal + $runningElecBal + $runningWaterBal;
            return $tenant;
        });

        return view('admins.locations.move_out.index', compact('tenants', 'locations', 'selectedLocation', 'locationId'));
    }
}
