<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\School;
use App\Services\TenantService;

class IdentifyTenant
{
    protected TenantService $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Extract the subdomain (e.g., 'hbschool' from 'hbschool.yoursms.com')
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];

        // Find the active school matching the subdomain
        $school = School::where('slug', $subdomain)
            ->where('status', 'active')
            ->firstOrFail();

        // Dynamically configure the database connection for this tenant
        $this->tenantService->configureConnection($school->database_name);

        return $next($request);
    }
}
