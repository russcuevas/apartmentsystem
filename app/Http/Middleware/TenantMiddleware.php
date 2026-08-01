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

        return $next($request);
    }
}
