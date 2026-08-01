<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TenantDashboardController extends Controller
{
    public function TenantDashboardPage()
    {
        return view("tenants.dashboard.index");
    }
}
