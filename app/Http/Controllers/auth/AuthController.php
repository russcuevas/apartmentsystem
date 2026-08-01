<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function AdminLoginPage()
    {
        return view('auth.admins.login');
    }

    public function TenantLoginPage()
    {
        return view('auth.tenants.login');
    }
}
