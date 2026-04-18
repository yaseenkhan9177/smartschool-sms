<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Jobs\ProvisionTenantJob;
use Illuminate\Http\Request;

class SchoolApprovalController extends Controller
{
    public function approve(School $school)
    {
        if ($school->status !== 'pending') {
            return back()->with('error', 'School is already approved or not pending.');
        }

        // Improvement #2: Regex for DB Name Safety
        $safeSlug = preg_replace('/[^A-Za-z0-9_]/', '', $school->slug);
        $databaseName = 'sms_tenant_' . $safeSlug;

        // Dispatch the queue job (Improvement #5)
        dispatch(new ProvisionTenantJob($school, $databaseName));

        // Mark as provisioning so the UI shows it's in progress
        $school->update(['status' => 'provisioning']);

        return back()->with('success', 'School approved! The database environment is being created in the background. You will be notified once it is ready.');
    }
}
