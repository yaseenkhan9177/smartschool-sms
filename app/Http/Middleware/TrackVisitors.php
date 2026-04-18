<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class TrackVisitors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only run visitor tracking on the homepage
        if ($request->path() !== '/' && $request->path() !== '') {
            return $next($request);
        }

        $ip = $request->ip();
        $today = now()->toDateString();

        // Only count 1 visit per IP per day
        if (!DB::table('site_visitors')->where('ip_address', $ip)->where('visit_date', $today)->exists()) {
            $location = 'Unknown';
            try {
                // Skip IP lookup for localhost
                if ($ip !== '127.0.0.1' && $ip !== '::1') {
                    // Use a strict 2-second timeout to prevent blocking requests
                    $context = stream_context_create([
                        'http' => [
                            'timeout' => 2,
                        ],
                    ]);
                    $json = @file_get_contents("http://ip-api.com/json/{$ip}", false, $context);
                    if ($json) {
                        $data = json_decode($json);
                        if (isset($data->country) && isset($data->city)) {
                            $location = $data->city . ', ' . $data->country;
                        }
                    }
                } else {
                    $location = 'Localhost';
                }
            } catch (\Exception $e) {
                // Fail silently to not block request
            }

            DB::table('site_visitors')->insert([
                'ip_address' => $ip,
                'visit_date' => $today,
                'location'   => $location,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $next($request);
    }
}
