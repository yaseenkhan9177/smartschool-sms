<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Accountant;
use App\Models\User;

class UnifiedAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);



        // 1. Check Admin (User Model)
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            if ($user->role === 'student') {
                $request->session()->put('role', 'student');
                return redirect()->route('student.dashboard');
            } elseif ($user->role === 'teacher') {
                $request->session()->put('role', 'teacher');
                return redirect()->route('teacher.dashboard');
            } elseif ($user->role === 'accountant') {
                $request->session()->put('role', 'accountant');
                return redirect()->route('accountant.dashboard');
            }

            $request->session()->put('role', 'admin');
            return redirect()->route('school admin.dashboard');
        }

        // 2. Check Teacher
        $teacher = Teacher::where('email', $request->email)->first();
        if ($teacher && Hash::check($request->password, $teacher->password)) {
            Auth::guard('teacher')->login($teacher);
            $request->session()->put('teacher_id', $teacher->id);
            $request->session()->put('teacher_name', $teacher->name);
            $request->session()->put('role', 'teacher');
            return redirect()->route('teacher.dashboard');
        }

        // 3. Check Student
        $student = Student::where('email', $request->email)->first();
        if ($student && Hash::check($request->password, $student->password)) {
            if ($student->status !== 'approved') {
                return back()->with('error', 'Your account is pending approval. Please contact the administrator.');
            }

            Auth::guard('student')->login($student);
            $request->session()->put('student_id', $student->id);
            $request->session()->put('student_name', $student->name);
            $request->session()->put('role', 'student');
            return redirect()->route('student.dashboard');
        }

        // 4. Check Accountant
        $accountant = Accountant::withoutGlobalScope(\App\Models\Scopes\SchoolScope::class)->where('email', $request->email)->first();
        if ($accountant && Hash::check($request->password, $accountant->password)) {
            Auth::guard('accountant')->login($accountant);
            $request->session()->put('accountant_id', $accountant->id);
            $request->session()->put('accountant_name', $accountant->name);
            $request->session()->put('role', 'accountant');
            return redirect()->route('accountant.dashboard');
        }




        // 5. Check Parent
        // Search by email, since user wants to use email as login ID
        $parent = \App\Models\SchoolParent::where('email', $request->email)->first();

        if ($parent && Hash::check($request->password, $parent->password)) {
            Auth::guard('parent')->login($parent);
            $request->session()->put('parent_id', $parent->id);
            $request->session()->put('parent_name', $parent->name);
            $request->session()->put('role', 'parent');
            return redirect()->route('parent.dashboard');
        }



        return back()->with('error', 'Invalid email or password.');
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Logout web guard
        Auth::guard('teacher')->logout();
        Auth::guard('accountant')->logout();
        Auth::guard('super_admin')->logout();
        Auth::guard('parent')->logout();

        $request->session()->flush();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
