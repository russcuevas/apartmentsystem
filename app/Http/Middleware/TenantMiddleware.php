<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('tenant')->check()) {
            return redirect()->route('tenant.login.page')->withErrors([
                'phone_number' => 'Access denied. You must be logged in as a tenant.',
            ]);
        }

        $tenant = Auth::guard('tenant')->user();
        if ($tenant && $tenant->rentInformation && $tenant->rentInformation->move_out) {
            Auth::guard('tenant')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('tenant.login.page')->withErrors([
                'phone_number' => 'Your account has been marked as Moved Out. Access denied.',
            ]);
        }

        return $next($request);
    }
}
