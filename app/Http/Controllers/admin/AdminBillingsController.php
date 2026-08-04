<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TenantBillingsRent;
use App\Models\Locations;
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

        // 12 calendar months list
        $allMonths = [
            'January', 'February', 'March', 'April',
            'May', 'June', 'July', 'August',
            'September', 'October', 'November', 'December'
        ];

        // Fetch all tenant rent billings with eager loading
        $query = TenantBillingsRent::with(['tenant.location', 'tenant.rentInformation']);

        if ($selectedLocationId) {
            $query->whereHas('tenant', function ($q) use ($selectedLocationId) {
                $q->where('location_id', $selectedLocationId);
            });
        }

        if ($selectedYear) {
            $query->where(function ($q) use ($selectedYear) {
                $q->whereYear('due_date', $selectedYear)
                  ->orWhereYear('created_at', $selectedYear);
            });
        }

        $allBillings = $query->get();

        // Aggregate statistics per month
        $monthsData = collect($allMonths)->map(function ($monthName) use ($allBillings) {
            $monthBillings = $allBillings->filter(function ($billing) use ($monthName) {
                return strcasecmp(trim($billing->billing_month), trim($monthName)) === 0;
            })->values();

            $totalTenants = $monthBillings->count();
            $totalAmount = $monthBillings->sum('rent_amount');
            $totalBalance = $monthBillings->sum('balance');
            $paidCount = $monthBillings->where('status', 'Paid')->count();
            $unpaidCount = $monthBillings->where('status', '!=', 'Paid')->count();

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
                'billings'      => $monthBillings,
            ];
        });

        return view('admins.locations.billings.index', compact(
            'monthsData',
            'selectedYear',
            'availableYears',
            'locations',
            'selectedLocationId',
            'selectedLocation'
        ));
    }
}

