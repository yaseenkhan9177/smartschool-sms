<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function index()
    {
        // 1. Dashboard Widget Data
        $activeLicenses = \App\Models\LicenseKey::where('status', 'active')->count();
        $expiredLicenses = \App\Models\LicenseKey::where('status', 'expired')->count();
        $pendingKeys = \App\Models\LicenseKey::where('is_auto_generated', true)->where('status', 'inactive')->count();

        // 2. Active Licenses Table
        $licenses = \App\Models\LicenseKey::where('status', '!=', 'inactive')
            ->with('school')
            ->orderBy('expiry_date', 'asc') // Expiry soonest first
            ->get();

        return view('super_admin.licenses.index', compact('activeLicenses', 'expiredLicenses', 'pendingKeys', 'licenses'));
    }

    public function create()
    {
        $schools = \App\Models\User::where('role', 'admin')->get();
        return view('super_admin.licenses.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:users,id',
            'plan_duration' => 'required',
            'start_date' => 'required|date',
        ]);

        // Generate Key
        $key = strtoupper(implode('-', str_split(bin2hex(random_bytes(8)), 4)));

        // Calculate Expiry
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $expiryDate = $startDate->copy();

        switch ($request->plan_duration) {
            case '1_week':
                $expiryDate->addWeek();
                break;
            case '1_month':
                $expiryDate->addMonth();
                break;
            case '6_months':
                $expiryDate->addMonths(6);
                break;
            case '1_year':
                $expiryDate->addYear();
                break;
        }

        \App\Models\LicenseKey::create([
            'school_id' => $request->school_id,
            'license_key' => $key,
            'plan_duration' => $request->plan_duration,
            'start_date' => $startDate,
            'expiry_date' => $expiryDate,
            'status' => 'active',
        ]);

        return redirect()->route('super_admin.licenses.index')->with('success', 'License key generated successfully!');
    }

    public function pending()
    {
        $pendingKeys = \App\Models\LicenseKey::where('is_auto_generated', true)
            ->where('status', 'inactive')
            ->with('school')
            ->get();

        return view('super_admin.licenses.pending', compact('pendingKeys'));
    }

    public function activate($id)
    {
        $key = \App\Models\LicenseKey::findOrFail($id);
        $key->status = 'active';
        $key->save();

        return redirect()->back()->with('success', 'Pending key activated successfully!');
    }
}
