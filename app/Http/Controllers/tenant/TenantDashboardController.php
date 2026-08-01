<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantDashboardController extends Controller
{
    /**
     * Display Tenant Dashboard with personal tenant details & rent information.
     */
    public function TenantDashboardPage()
    {
        $tenant = Auth::guard('tenant')->user()->load(['location', 'rentInformation']);
        return view("tenants.dashboard.index", compact('tenant'));
    }
}
