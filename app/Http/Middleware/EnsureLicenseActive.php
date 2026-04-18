<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureLicenseActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for School Admins
        if (Auth::guard('web')->check() && Auth::user()->role === 'admin') {
            $user = Auth::user();

            $license = \App\Models\LicenseKey::where('school_id', $user->id)
                ->where('status', 'active')
                ->where('expiry_date', '>=', now()->toDateString())
                ->first();

            if (!$license) {
                // If checking for JSON/AJAX, return 403 JSON
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Your school license has expired.'], 403);
                }

                // Otherwise show error page
                return response()->view('errors.license_expired', [], 403);
            }

            // Check for upcoming expiry (2 days or less)
            $expiryDate = \Carbon\Carbon::parse($license->expiry_date);
            $daysRemaining = (int) now()->diffInDays($expiryDate, false);

            if ($daysRemaining <= 2) {
                view()->share('licenseDaysRemaining', $daysRemaining);
            }
        }

        return $next($request);
    }
}
