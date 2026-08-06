<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TenantPayments;
use App\Models\TenantBillingsRent;
use App\Models\TenantBillingsElectricity;
use App\Models\TenantBillingsWater;
use App\Models\Locations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPaymentsController extends Controller
{
    /**
     * Display Admin Payments Overview with Year & Location filters.
     */
    public function index(Request $request)
    {
        $currentYear = (int) date('Y');
        $selectedYear = $request->get('year', (string) $currentYear);
        $selectedLocationId = $request->get('location_id');

        $availableYears = array_map('strval', range($currentYear - 2, $currentYear + 5));
        $locations = Locations::all();
        $selectedLocation = $selectedLocationId ? Locations::find($selectedLocationId) : null;

        $allMonths = [
            'January', 'February', 'March', 'April',
            'May', 'June', 'July', 'August',
            'September', 'October', 'November', 'December'
        ];

        $paymentsQuery = TenantPayments::with([
            'tenant.location',
            'tenant.rentInformation',
            'receiver',
            'billingRent',
            'billingElectricity',
            'billingWater'
        ]);

        if ($selectedLocationId) {
            $paymentsQuery->whereHas('tenant', function ($q) use ($selectedLocationId) {
                $q->where('location_id', $selectedLocationId);
            });
        }

        if ($selectedYear) {
            $paymentsQuery->where(function ($q) use ($selectedYear) {
                $q->whereYear('created_at', $selectedYear);
            });
        }

        $allPayments = $paymentsQuery->orderBy('created_at', 'desc')->get();

        // Aggregate per month (January - December)
        $monthsData = collect($allMonths)->map(function ($monthName) use ($allPayments) {
            $monthPayments = $allPayments->filter(function ($payment) use ($monthName) {
                return strcasecmp(trim($payment->billing_month), trim($monthName)) === 0;
            })->values();

            $totalCount = $monthPayments->groupBy(function ($payment) {
                $tenantName = $payment->tenant ? $payment->tenant->fullname : 'N/A';
                $locationName = ($payment->tenant && $payment->tenant->location) ? $payment->tenant->location->location_name : 'N/A';
                $room = ($payment->tenant && $payment->tenant->rentInformation) ? $payment->tenant->rentInformation->room : 'N/A';
                return $payment->tenant_id ?: ($tenantName . '_' . $locationName . '_' . $room);
            })->count();
            $totalAmount = $monthPayments->whereIn('status', ['Approved', 'Accepted'])->sum('amount');

            $approvedCount = $monthPayments->whereIn('status', ['Approved', 'Accepted'])->count();
            $pendingCount = $monthPayments->where('status', 'Pending')->count();
            $declinedCount = $monthPayments->where('status', 'Declined')->count();

            if ($totalCount === 0) {
                $status = 'No Payments';
                $statusClass = 'neutral';
            } elseif ($pendingCount > 0) {
                $status = 'Pending Verification';
                $statusClass = 'warning';
            } elseif ($declinedCount > 0 && $approvedCount === 0) {
                $status = 'Declined';
                $statusClass = 'danger';
            } else {
                $status = 'Approved';
                $statusClass = 'success';
            }

            return [
                'month'          => $monthName,
                'total_count'    => $totalCount,
                'total_amount'   => $totalAmount,
                'approved_count' => $approvedCount,
                'pending_count'  => $pendingCount,
                'declined_count' => $declinedCount,
                'status'         => $status,
                'status_class'   => $statusClass,
                'payments'       => $monthPayments,
            ];
        });

        return view('admins.locations.payments.index', compact(
            'monthsData',
            'selectedYear',
            'availableYears',
            'locations',
            'selectedLocationId',
            'selectedLocation',
            'allMonths'
        ));
    }

    /**
     * Approve a submitted payment (tagged as Approved).
     * Syncs electricity_amount / water_amount to balance/rent_amount and file_electricity / file_water to proof_of_billing.
     */
    public function approve($id)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $payment = TenantPayments::findOrFail($id);
        $payment->update([
            'status'      => 'Approved',
            'received_by' => $admin->id,
        ]);

        $category = strtolower(trim($payment->payment_type ?? ''));

        // 1. Handle Rent Billing Update
        if ($payment->tenant_billings_rent_id || $category === 'rent' || $category === 'monthly rental') {
            $rent = null;
            if ($payment->tenant_billings_rent_id) {
                $rent = TenantBillingsRent::find($payment->tenant_billings_rent_id);
            }
            if (!$rent) {
                $rent = TenantBillingsRent::where('tenant_id', $payment->tenant_id)
                    ->where('billing_month', $payment->billing_month)
                    ->first();
            }
            if ($rent) {
                $totalRentPaid = TenantPayments::where('tenant_id', $payment->tenant_id)
                    ->where('billing_month', $payment->billing_month)
                    ->whereIn('payment_type', ['Rent', 'Monthly Rental'])
                    ->whereIn('status', ['Approved', 'Accepted'])
                    ->sum('amount');

                $newBalance = max(0, (float) $rent->rent_amount - (float) $totalRentPaid);
                $newStatus = ($newBalance <= 0) ? 'Paid' : (($totalRentPaid > 0) ? 'Partial' : 'Unpaid');
                $rent->update([
                    'balance' => $newBalance,
                    'status'  => $newStatus,
                ]);
            }
        }

        // 2. Handle Electricity Billing Sync (tenant_billings_electricities)
        if ($payment->tenant_billings_electricity_id || $category === 'electricity' || !empty($payment->file_electricity) || !empty($payment->electricity_amount)) {
            $elecBilling = null;
            if ($payment->tenant_billings_electricity_id) {
                $elecBilling = TenantBillingsElectricity::find($payment->tenant_billings_electricity_id);
            }
            if (!$elecBilling) {
                $elecBilling = TenantBillingsElectricity::where('tenant_id', $payment->tenant_id)
                    ->where('billing_month', $payment->billing_month)
                    ->first();
            }

            $totalElecPaid = TenantPayments::where('tenant_id', $payment->tenant_id)
                ->where('billing_month', $payment->billing_month)
                ->whereIn('payment_type', ['Electricity'])
                ->whereIn('status', ['Approved', 'Accepted'])
                ->sum('amount');

            $elecTotal = (float) ($payment->electricity_amount ?? ($elecBilling ? $elecBilling->rent_amount : $payment->amount));
            $baseBalance = $elecBilling ? (float) $elecBilling->rent_amount : $elecTotal;
            if ($baseBalance <= 0) {
                $baseBalance = $elecTotal;
            }
            $newBalance = max(0, $baseBalance - (float) $totalElecPaid);
            $newStatus = ($newBalance <= 0) ? 'Paid' : (($totalElecPaid > 0) ? 'Partial' : 'Unpaid');

            $proofElec = $payment->file_electricity ?? $payment->proof_of_billing ?? null;

            if ($elecBilling) {
                $elecBilling->update([
                    'rent_amount'      => $elecTotal > 0 ? $elecTotal : $elecBilling->rent_amount,
                    'balance'          => $newBalance,
                    'proof_of_billing' => $proofElec ?? $elecBilling->proof_of_billing,
                    'status'           => $newStatus,
                ]);
            } else {
                $elecBilling = TenantBillingsElectricity::create([
                    'tenant_id'        => $payment->tenant_id,
                    'billing_month'    => $payment->billing_month,
                    'due_date'         => date('Y-m-20'),
                    'rent_amount'      => $elecTotal,
                    'balance'          => $newBalance,
                    'proof_of_billing' => $proofElec,
                    'status'           => $newStatus,
                ]);
            }

            $payment->update(['tenant_billings_electricity_id' => $elecBilling->id]);
        }

        // 3. Handle Water Billing Sync (tenant_billings_waters)
        if ($payment->tenant_billings_water_id || $category === 'water' || !empty($payment->file_water) || !empty($payment->water_amount)) {
            $waterBilling = null;
            if ($payment->tenant_billings_water_id) {
                $waterBilling = TenantBillingsWater::find($payment->tenant_billings_water_id);
            }
            if (!$waterBilling) {
                $waterBilling = TenantBillingsWater::where('tenant_id', $payment->tenant_id)
                    ->where('billing_month', $payment->billing_month)
                    ->first();
            }

            $totalWaterPaid = TenantPayments::where('tenant_id', $payment->tenant_id)
                ->where('billing_month', $payment->billing_month)
                ->whereIn('payment_type', ['Water'])
                ->whereIn('status', ['Approved', 'Accepted'])
                ->sum('amount');

            $waterTotal = (float) ($payment->water_amount ?? ($waterBilling ? $waterBilling->rent_amount : $payment->amount));
            $baseBalance = $waterBilling ? (float) $waterBilling->rent_amount : $waterTotal;
            if ($baseBalance <= 0) {
                $baseBalance = $waterTotal;
            }
            $newBalance = max(0, $baseBalance - (float) $totalWaterPaid);
            $newStatus = ($newBalance <= 0) ? 'Paid' : (($totalWaterPaid > 0) ? 'Partial' : 'Unpaid');

            $proofWater = $payment->file_water ?? $payment->proof_of_billing ?? null;

            if ($waterBilling) {
                $waterBilling->update([
                    'rent_amount'      => $waterTotal > 0 ? $waterTotal : $waterBilling->rent_amount,
                    'balance'          => $newBalance,
                    'proof_of_billing' => $proofWater ?? $waterBilling->proof_of_billing,
                    'status'           => $newStatus,
                ]);
            } else {
                $waterBilling = TenantBillingsWater::create([
                    'tenant_id'        => $payment->tenant_id,
                    'billing_month'    => $payment->billing_month,
                    'due_date'         => date('Y-m-25'),
                    'rent_amount'      => $waterTotal,
                    'balance'          => $newBalance,
                    'proof_of_billing' => $proofWater,
                    'status'           => $newStatus,
                ]);
            }

            $payment->update(['tenant_billings_water_id' => $waterBilling->id]);
        }

        return redirect()->back()->with('success', 'Payment #' . $payment->id . ' has been tagged as Approved.');
    }

    /**
     * Decline a submitted payment (tagged as Declined).
     */
    public function decline($id)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $payment = TenantPayments::findOrFail($id);
        $payment->update([
            'status'      => 'Declined',
            'received_by' => $admin->id,
        ]);

        return redirect()->back()->with('success', 'Payment #' . $payment->id . ' has been tagged as Declined.');
    }
}
