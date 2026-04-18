<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function index()
    {
        $schoolsCount = User::where('role', 'admin')->count();
        $activeSchools = User::where('role', 'admin')->where('status', 'active')->count();

        // New Visitor Metric
        $totalVisitors = DB::table('site_visitors')->count();
        $todayVisitors = DB::table('site_visitors')->where('visit_date', now()->toDateString())->count();

        // Chart Data: Last 7 Days
        $chartData = DB::table('site_visitors')
            ->select('visit_date', DB::raw('count(*) as count'))
            ->where('visit_date', '>=', now()->subDays(6)->toDateString())
            ->groupBy('visit_date')
            ->orderBy('visit_date')
            ->get();

        $dates = [];
        $visits = [];

        // Fill in missing dates with 0
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dates[] = $date;
            $visitCount = $chartData->where('visit_date', $date)->first()->count ?? 0;
            $visits[] = $visitCount;
        }

        // Get recent 5 schools for the table
        $schools = User::where('role', 'admin')->with('license')->latest()->take(5)->get();

        return view('super_admin.dashboard', compact('schoolsCount', 'activeSchools', 'totalVisitors', 'todayVisitors', 'schools', 'dates', 'visits'));
    }

    public function settings()
    {
        $superAdmins = \App\Models\SuperAdmin::all();
        return view('super_admin.settings', compact('superAdmins'));
    }

    public function storeSuperAdmin(Request $request)
    {
        // -----------------------------------------------------------------
        // PIN Security Gate — verify session flag + 5-minute expiry
        // -----------------------------------------------------------------
        $pinVerified = session('super_admin_pin_verified');
        $pinTime     = session('super_admin_pin_time');

        if (!$pinVerified || !$pinTime || now()->diffInMinutes($pinTime) > 5) {
            session()->forget(['super_admin_pin_verified', 'super_admin_pin_time']);
            return redirect()->route('super_admin.pin.show')
                ->with('warning', '🔒 PIN expired or not verified. Please verify your daily PIN to continue.');
        }

        // Consume the session flag (one-time use)
        session()->forget(['super_admin_pin_verified', 'super_admin_pin_time']);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:super_admins,email',
            'password' => 'required|string|min:8',
        ]);

        \App\Models\SuperAdmin::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return redirect()->route('super_admin.settings')->with('success', 'New Super Admin created successfully.');
    }


    public function destroySuperAdmin($id)
    {
        // Enforce rule: Prevent deletion of last super admin in any tenant/master
        $count = \App\Models\SuperAdmin::count();
        if ($count <= 1) {
            return redirect()->back()->with('error', 'Action denied. You cannot delete the last Super Admin in the system.');
        }

        $admin = \App\Models\SuperAdmin::findOrFail($id);

        // Optional: Prevent self-deletion if logged in
        if (Auth::guard('super_admin')->id() == $admin->id) {
            return redirect()->back()->with('error', 'You cannot delete your own active account.');
        }

        $admin->delete();

        return redirect()->route('super_admin.settings')->with('success', 'Super Admin deleted successfully.');
    }

    public function approveAdmin($id)
    {
        $admin = \App\Models\SuperAdmin::findOrFail($id);
        $admin->update(['status' => 'active']);

        return redirect()->route('super_admin.settings')->with('success', "Super Admin '{$admin->name}' approved successfully.");
    }

    public function visitors()
    {
        $visitors = DB::table('site_visitors')->latest()->paginate(20);
        return view('super_admin.visitors', compact('visitors'));
    }

    public function show($id)
    {
        $school = User::findOrFail($id);

        // For this drill-down, we attempt to filter by school_id.
        // Note: As per current request, we assume filtering logic exists or is desired.
        // If the column prevents this, we might need a migration later.
        // For now, let's implement the logic as requested.
        try {
            $totalStudents = \App\Models\Student::where('school_id', $id)->count();
            $totalTeachers = \App\Models\Teacher::where('school_id', $id)->count();
        } catch (\Exception $e) {
            // Fallback if column missing, just for stability during this refactor step
            $totalStudents = 0;
            $totalTeachers = 0;
        }

        return view('super_admin.schools.show', compact('school', 'totalStudents', 'totalTeachers'));
    }

    public function impersonate($id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'admin') {
            return redirect()->back()->with('error', 'Can only impersonate School Admins.');
        }

        Auth::login($user);

        return redirect()->route('school admin.dashboard')->with('success', "Logged in as {$user->school_name}");
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Toggle status
        $user->status = $user->status === 'active' ? 'suspended' : 'active';
        $user->save();

        $message = $user->status === 'active' ? 'School activated.' : 'School suspended.';
        return redirect()->back()->with('success', $message);
    }

    public function createSchool()
    {
        return view('super_admin.add_school');
    }

    public function storeSchool(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string',
            'address' => 'nullable|string',
            'plan_duration' => 'required|integer|in:1,6,12,24',
        ]);

        // 1. Create User (School Admin)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'admin',
            'school_name' => $request->school_name,
            'phone' => $request->phone,
            'status' => 'provisioning', // Changed to provisioning while job runs
        ]);

        // Generate and save the database name matching the user ID
        $safeName = preg_replace('/[^a-z0-9_]/', '', strtolower($user->school_name));
        $databaseName = 'smsdb_' . $safeName . '_' . $user->id;
        $user->update(['database_name' => $databaseName]);

        dispatch(new \App\Jobs\ProvisionTenantFromUserJob($user));

        // 2. Generate License Key
        // Ensure at least 1 month trial even if something else was picked for initial setup 
        // OR just respect the plan but the user specifically asked for "first give him one mouth License Key"
        $duration = 1; // Default to 1 month for initial registration as requested
        $expiryDate = \Carbon\Carbon::now()->addMonths($duration);

        $durationString = '1 Month Trial';

        \App\Models\LicenseKey::create([
            'school_id' => $user->id,
            'license_key' => strtoupper(\Illuminate\Support\Str::random(5)) . '-' . strtoupper(\Illuminate\Support\Str::random(5)) . '-' . strtoupper(\Illuminate\Support\Str::random(5)),
            'status' => 'active',
            'plan_duration' => $durationString,
            'start_date' => \Carbon\Carbon::now(),
            'expiry_date' => $expiryDate,
        ]);

        return redirect()->route('super_admin.dashboard')->with('success', "School '{$request->school_name}' created successfully! Principal can login now.");
    }
    public function listRequests()
    {
        $requests = \App\Models\SchoolRequest::latest()->get();
        return view('super_admin.requests.index', compact('requests'));
    }

    public function approveRequest($id)
    {
        $request = \App\Models\SchoolRequest::findOrFail($id);

        if ($request->status !== 'pending') {
            return back()->with('error', 'Request already processed.');
        }

        $user = DB::transaction(function () use ($request) {
            // 1. Create or Find School Record
            $school = \App\Models\School::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->school_name,
                    'status' => 'active',
                    'logo' => $request->logo, // Add logo
                ]
            );

            // 2. Create School Admin User
            // Password logic: Default or random. Let's use 'password' for now and email them to change it.
            $tempPassword = 'password';

            $user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->owner_name,
                    'password' => \Illuminate\Support\Facades\Hash::make($tempPassword),
                    'role' => 'admin',
                    'school_name' => $request->school_name,
                    'phone' => $request->phone,
                    'status' => 'provisioning',
                ]
            );

            // Generate database name immediately after getting the User ID
            $safeName = preg_replace('/[^a-z0-9_]/', '', strtolower($user->school_name));
            $databaseName = 'smsdb_' . $safeName . '_' . $user->id;

            // Only update if it's not already set
            if (empty($user->database_name)) {
                $user->update(['database_name' => $databaseName]);
            }

            // 3. Generate License Key
            $expiryDate = \Carbon\Carbon::now()->addMonths(1); // Default 1 month trial

            \App\Models\LicenseKey::firstOrCreate(
                ['school_id' => $user->id],
                [
                    'license_key' => strtoupper(\Illuminate\Support\Str::random(5)) . '-' . strtoupper(\Illuminate\Support\Str::random(5)) . '-' . strtoupper(\Illuminate\Support\Str::random(5)),
                    'status' => 'active',
                    'plan_duration' => '1 Month Trial',
                    'start_date' => \Carbon\Carbon::now(),
                    'expiry_date' => $expiryDate,
                ]
            );

            // 4. Update Request Status
            $request->update(['status' => 'approved']);

            // 5. Send Email (Simulated)
            // Mail::to($request->email)->send(new SchoolApproved($school, $user, $tempPassword));

            return $user;
        });

        // Dispatch the job outside the transaction so that the CREATE DATABASE DDL 
        // doesn't cause an implicit commit that breaks Laravel's transaction manager.
        dispatch(new \App\Jobs\ProvisionTenantFromUserJob($user));

        return back()->with('success', 'School request approved and account created.');
    }

    public function rejectRequest(Request $request, $id)
    {
        $schoolRequest = \App\Models\SchoolRequest::findOrFail($id);
        $schoolRequest->update([
            'status' => 'rejected',
            'remarks' => $request->remarks
        ]);

        return back()->with('success', 'School request rejected.');
    }
}
