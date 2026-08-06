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
    public function AdminLoginRequest(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $admin = Auth::guard('admin')->user();
            return redirect()->route('admin.dashboard.page')->with('success', 'Welcome, ' . $admin->fullname);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle Admin Logout
     */
    public function AdminLogoutRequest(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login.page')->with('success', 'Logged out successfully');
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
            $tenant = Auth::guard('tenant')->user();
            return redirect()->route('tenant.dashboard.page')->with('success', 'Welcome, ' . trim($tenant->fullname));
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

        return redirect()->route('tenant.login.page')->with('success', 'Logged out successfully');
    }

    /**
     * Update Tenant Password from My Account modal
     */
    public function TenantChangePassword(Request $request)
    {
        $tenant = Auth::guard('tenant')->user();
        if (!$tenant) {
            return redirect()->route('tenant.login.page');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|string|min:6|confirmed',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $tenant->password)) {
            return back()->withErrors(['current_password' => 'The current password you provided is incorrect.']);
        }

        $tenant->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        $tenant->save();

        return back()->with('success', 'Your password has been updated successfully!');
    }
}
