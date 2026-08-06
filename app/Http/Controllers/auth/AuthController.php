<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\Admins;
use App\Mail\AdminResetPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
     * Handle Admin Send Password Reset Link
     */
    public function AdminSendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $admin = Admins::where('email', $request->email)->first();

        if (!$admin) {
            return back()->withErrors(['forgot_email' => 'We could not find an admin with that email address.'])->withInput();
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );

        try {
            Mail::to($request->email)->send(new AdminResetPasswordMail($token, $request->email));
            return back()->with('success', 'We have emailed your password reset link! Please check your inbox.');
        } catch (\Exception $e) {
            return back()->withErrors(['forgot_email' => 'Failed to send reset email: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show Admin Reset Password Page
     */
    public function AdminResetPasswordPage(Request $request, $token)
    {
        return view('auth.admins.reset-password', [
            'token' => $token,
            'email' => $request->query('email', $request->email),
        ]);
    }

    /**
     * Handle Admin Reset Password Submission
     */
    public function AdminResetPasswordUpdate(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|string|min:6|confirmed',
        ]);

        $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['email' => 'This password reset link is invalid or has expired.']);
        }

        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'This password reset link has expired. Please request a new one.']);
        }

        $admin = Admins::where('email', $request->email)->first();
        if (!$admin) {
            return back()->withErrors(['email' => 'We could not find an admin with that email address.']);
        }

        $admin->password = Hash::make($request->password);
        $admin->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('admin.login.page')->with('success', 'Your password has been reset successfully! You can now log in.');
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
     * Update Admin Account details and password
     */
    public function AdminUpdateAccount(Request $request)
    {
        /** @var \App\Models\Admins $admin */
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login.page');
        }

        $request->validate([
            'fullname'     => 'required|string|max:255',
            'email'        => 'required|email|unique:admins,email,' . $admin->id,
            'phone_number' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($request->filled('new_password')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'The current password you entered is incorrect.']);
            }
            $admin->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        }

        $admin->fullname     = $request->fullname;
        $admin->email        = $request->email;
        $admin->phone_number = $request->phone_number;
        $admin->save();

        return back()->with('success', 'Admin account details updated successfully!');
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
        /** @var \App\Models\Tenants $tenant */
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
