<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\SuperAdmin;


class SuperAdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('super_admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('super_admin')->attempt($credentials)) {
            $admin = Auth::guard('super_admin')->user();
            
            if ($admin->status !== 'active') {
                Auth::guard('super_admin')->logout();
                return back()->withErrors([
                    'email' => 'Your account is pending approval from another Super Admin.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->route('super_admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('super_admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('super_admin.login');
    }

    // -------------------------------------------------------------------------
    // Bootstrap Registration (First Install Only)
    // -------------------------------------------------------------------------

    public function showRegisterForm()
    {
        // Registration is allowed if PIN is verified, regardless of admin count
        // PIN verification ensures only authorized users can reach here.
        if (!session('super_admin_pin_verified')) {
            return redirect()->route('super_admin.pin.show')
                ->with('info', 'Please verify your daily PIN to proceed with registration.');
        }

        return view('super_admin.auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:super_admins,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // First admin is active, subsequent are pending
        $status = SuperAdmin::count() === 0 ? 'active' : 'pending';

        SuperAdmin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => $status,
        ]);

        session()->forget(['super_admin_pin_verified', 'super_admin_pin_time']);

        if ($status === 'active') {
            return redirect()->route('super_admin.login')
                ->with('success', 'Super Admin created successfully. Please login.');
        } else {
            return redirect()->route('super_admin.login')
                ->with('info', 'Registration successful! Your account is pending approval from an existing Super Admin.');
        }
    }


    // -------------------------------------------------------------------------
    // Dynamic Daily PIN Gate for "Add New Super Admin" action
    // -------------------------------------------------------------------------

    public function showPinForm()
    {
        // If an admin exists and user is NOT logged in, they shouldn't be here (bootstrap case)
        if (SuperAdmin::count() > 0 && !Auth::guard('super_admin')->check()) {
            return redirect()->route('super_admin.login');
        }

        return view('super_admin.auth.pin_verify');
    }

    public function verifyPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:6',
        ]);

        $key = 'super-admin-pin-' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'pin' => "Too many failed attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        $correctPin = now()->format('dm') . config('security.super_admin_pin_secret');

        if ($request->pin !== $correctPin) {
            RateLimiter::hit($key, 60);
            $remaining = 5 - RateLimiter::attempts($key);
            return back()->withErrors([
                'pin' => "Invalid PIN. You have {$remaining} attempt(s) remaining.",
            ]);
        }

        RateLimiter::clear($key);
        session([
            'super_admin_pin_verified' => true,
            'super_admin_pin_time'     => now(),
        ]);

        // If it's a fresh install (Bootstrap), go to register
        if (SuperAdmin::count() === 0) {
            return redirect()->route('super_admin.register')
                ->with('success', '✅ PIN verified. Please create your first Super Admin account.');
        }

        // Otherwise (Settings flow), go back to settings
        return redirect()->route('super_admin.settings')
            ->with('success', '✅ PIN verified successfully. You may now add a Super Admin.');
    }
}
