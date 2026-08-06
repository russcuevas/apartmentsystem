<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\TenantBillingsRent;
use App\Models\TenantBillingsElectricity;
use App\Models\TenantBillingsWater;
use App\Models\TenantPayments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantBillingsController extends Controller
{
    /**
     * Display Tenant Billings Overview (UI datatable by month & year).
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

        // 1. Fetch Tenant's Rent Billings
        $rentBillings = TenantBillingsRent::with(['payments'])
            ->where('tenant_id', $tenant->id)
            ->where(function ($q) use ($selectedYear) {
                $q->whereYear('due_date', $selectedYear)->orWhereYear('created_at', $selectedYear);
            })->get();

        // 2. Fetch Tenant's Electricity Billings
        $elecBillings = TenantBillingsElectricity::with(['payments'])
            ->where('tenant_id', $tenant->id)
            ->where(function ($q) use ($selectedYear) {
                $q->whereYear('due_date', $selectedYear)->orWhereYear('created_at', $selectedYear);
            })->get();

        // 3. Fetch Tenant's Water Billings
        $waterBillings = TenantBillingsWater::with(['payments'])
            ->where('tenant_id', $tenant->id)
            ->where(function ($q) use ($selectedYear) {
                $q->whereYear('due_date', $selectedYear)->orWhereYear('created_at', $selectedYear);
            })->get();

        // 4. Fetch Tenant's All Payments for the year
        $allPayments = TenantPayments::where('tenant_id', $tenant->id)
            ->whereYear('created_at', $selectedYear)
            ->orderBy('created_at', 'desc')
            ->get();

        // Aggregate monthly billings data
        $monthsData = collect($allMonths)->map(function ($monthName) use ($rentBillings, $elecBillings, $waterBillings, $allPayments, $tenant) {
            $rent = $rentBillings->first(fn($b) => strcasecmp(trim($b->billing_month), trim($monthName)) === 0);
            $elec = $elecBillings->first(fn($b) => strcasecmp(trim($b->billing_month), trim($monthName)) === 0);
            $water = $waterBillings->first(fn($b) => strcasecmp(trim($b->billing_month), trim($monthName)) === 0);

            $monthPayments = $allPayments->filter(fn($p) => strcasecmp(trim($p->billing_month), trim($monthName)) === 0)->values();
            $approvedPayments = $monthPayments->filter(fn($p) => in_array($p->status, ['Approved', 'Accepted']));

            $rentPaid  = (float) $approvedPayments->filter(fn($p) => strcasecmp(trim($p->payment_type ?? ''), 'Rent') === 0 || strcasecmp(trim($p->payment_type ?? ''), 'Monthly Rental') === 0)->sum('amount');
            $elecPaid  = (float) $approvedPayments->filter(fn($p) => strcasecmp(trim($p->payment_type ?? ''), 'Electricity') === 0)->sum('amount');
            $waterPaid = (float) $approvedPayments->filter(fn($p) => strcasecmp(trim($p->payment_type ?? ''), 'Water') === 0)->sum('amount');

            // Default base rent from tenant info if not billed yet
            $baseRent = $rent ? (float) $rent->rent_amount : (float) ($tenant->rentInformation->monthly_rental ?? 0);
            $rentAmount = $rent ? (float) $rent->rent_amount : 0;
            $rentBal    = max(0, $rentAmount - $rentPaid);
            $rentDueDate = $rent ? ($rent->due_date ? date('M d, Y', strtotime($rent->due_date)) : 'N/A') : 'N/A';
            $rentStatus  = $rent ? ($rentBal <= 0 ? 'Paid' : ($rentPaid > 0 ? 'Partial' : 'Unpaid')) : 'Not Billed';

            $elecAmount = $elec ? (float) $elec->rent_amount : 0;
            $elecBal    = max(0, $elecAmount - $elecPaid);
            $elecDueDate = $elec ? ($elec->due_date ? date('M d, Y', strtotime($elec->due_date)) : 'N/A') : 'N/A';
            $elecStatus  = $elec ? ($elecBal <= 0 ? 'Paid' : ($elecPaid > 0 ? 'Partial' : 'Unpaid')) : 'Not Billed';

            $waterAmount = $water ? (float) $water->rent_amount : 0;
            $waterBal    = max(0, $waterAmount - $waterPaid);
            $waterDueDate = $water ? ($water->due_date ? date('M d, Y', strtotime($water->due_date)) : 'N/A') : 'N/A';
            $waterStatus  = $water ? ($waterBal <= 0 ? 'Paid' : ($waterPaid > 0 ? 'Partial' : 'Unpaid')) : 'Not Billed';

            $totalBilled  = $rentAmount + $elecAmount + $waterAmount;
            $totalPaid    = $rentPaid + $elecPaid + $waterPaid;
            $totalBalance = max(0, $totalBilled - $totalPaid);

            $hasAnyBilling = ($rent !== null || $elec !== null || $water !== null);

            $pendingCount = $monthPayments->where('status', 'Pending')->count();

            if (!$hasAnyBilling && $monthPayments->count() === 0) {
                $overallStatus = 'No Billings';
                $overallStatusClass = 'neutral';
            } elseif ($pendingCount > 0) {
                $overallStatus = 'Pending Verification';
                $overallStatusClass = 'warning';
            } elseif ($totalBalance <= 0 && $totalBilled > 0) {
                $overallStatus = 'Paid';
                $overallStatusClass = 'success';
            } elseif ($totalPaid > 0) {
                $overallStatus = 'Partial';
                $overallStatusClass = 'warning';
            } else {
                $overallStatus = 'Unpaid';
                $overallStatusClass = 'danger';
            }

            return [
                'month'          => $monthName,
                'has_billing'    => $hasAnyBilling,
                'total_billed'   => $totalBilled,
                'total_paid'     => $totalPaid,
                'total_balance'  => $totalBalance,
                'status'         => $overallStatus,
                'status_class'   => $overallStatusClass,
                'rent'           => $rent ? [
                    'amount'      => $rentAmount,
                    'paid'        => $rentPaid,
                    'balance'     => $rentBal,
                    'due_date'    => $rentDueDate,
                    'status'      => $rentStatus,
                ] : null,
                'electricity'    => $elec ? [
                    'amount'      => $elecAmount,
                    'paid'        => $elecPaid,
                    'balance'     => $elecBal,
                    'due_date'    => $elecDueDate,
                    'status'      => $elecStatus,
                    'proof'       => $elec->proof_of_billing,
                ] : null,
                'water'          => $water ? [
                    'amount'      => $waterAmount,
                    'paid'        => $waterPaid,
                    'balance'     => $waterBal,
                    'due_date'    => $waterDueDate,
                    'status'      => $waterStatus,
                    'proof'       => $water->proof_of_billing,
                ] : null,
                'payments'       => $monthPayments,
            ];
        });

        return view('tenants.billings.index', compact(
            'tenant',
            'monthsData',
            'selectedYear',
            'availableYears',
            'allMonths'
        ));
    }
}
