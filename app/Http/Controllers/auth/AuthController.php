<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Admin Login Page
     */
    public function AdminLoginPage()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard.page');
        }
        return view('auth.admins.login');
    }

    /**
     * Handle Admin Login
     */
    public function AdminLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard.page');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle Admin Logout
     */
    public function AdminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login.page');
    }

    /**
     * Tenant Login Page
     */
    public function TenantLoginPage()
    {
        if (Auth::guard('tenant')->check()) {
            return redirect()->route('tenant.dashboard.page');
        }
        return view('auth.tenants.login');
    }

    /**
     * Handle Tenant Login using Phone Number
     */
    public function TenantLogin(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'password'     => 'required',
        ]);

        $credentials = $request->only('phone_number', 'password');

        if (Auth::guard('tenant')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('tenant.dashboard.page');
        }

        return back()->withErrors([
            'phone_number' => 'The provided phone number or password is incorrect.',
        ])->withInput($request->only('phone_number'));
    }

    /**
     * Handle Tenant Logout
     */
    public function TenantLogout(Request $request)
    {
        Auth::guard('tenant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('tenant.login.page');
    }
}
