<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class StudentAuthController extends Controller
{
    // Show register form
    public function showRegisterForm()
    {
        $classes = \App\Models\SchoolClass::all();
        return view('auth.register', compact('classes'));
    }

    // Show parent admission form
    public function showParentRegisterForm()
    {
        $classes = \App\Models\SchoolClass::all();
        return view('auth.parent_register', compact('classes'));
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'phone' => 'required|string|max:20',
            'parent_phone' => 'required|digits:11', // Ensuring validation to 11 digits
            'parent_name' => 'nullable|string|max:255',
            'roll_number' => 'nullable|string|max:50',
            'class_id' => 'nullable|exists:school_classes,id',
            'password' => 'required|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/students'), $imageName);
            $imagePath = 'uploads/students/' . $imageName;
        }

        Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'parent_phone' => $request->parent_phone,
            'parent_name' => $request->parent_name, // Store parent name
            'roll_number' => $request->roll_number, // Store roll number
            'class_id' => $request->class_id,
            'password' => Hash::make($request->password),
            'profile_image' => $imagePath,
            'status' => 'pending',
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please wait for admin approval.');
    }

    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ✅ Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $student = Student::where('email', $request->email)->first();

        if ($student && Hash::check($request->password, $student->password)) {
            // Store student session
            $request->session()->put('student_id', $student->id);
            $request->session()->put('student_name', $student->name);

            return redirect()->route('student.dashboard');
        }

        return back()->with('error', 'Invalid email or password.');
    }

    // ✅ Handle logout
    public function logout(Request $request)
    {
        $request->session()->forget(['student_id', 'student_name']);
        return redirect()->route('student.login')->with('success', 'Logged out successfully.');
    }

    // ✅ Show profile page
    public function profile()
    {
        // Get the logged-in student from session or auth
        $studentId = session('student_id'); // or Auth::guard('student')->id();

        $student = \App\Models\Student::find($studentId);

        if (!$student) {
            return redirect()->route('student.login')->with('error', 'Please log in first.');
        }

        // Calculate Stats (Similar to Dashboard)

        // 1. Attendance Rate
        $totalAttendance = \App\Models\Attendance::where('student_id', $studentId)->count();
        $presentCount = \App\Models\Attendance::where('student_id', $studentId)
            ->where('status', 'present')
            ->count();
        $attendanceRate = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100, 1) : 0;

        // 2. Grade Average
        $examResults = \App\Models\ExamResult::where('student_id', $studentId)->get();
        $totalObtained = $examResults->sum('obtained_marks');
        $totalMaxMarks = $examResults->sum('total_marks');
        $averagePercentage = $totalMaxMarks > 0 ? round(($totalObtained / $totalMaxMarks) * 100, 1) : 0;

        // Grade Letter Helper
        $gradeLetter = 'F';
        if ($averagePercentage >= 90) $gradeLetter = 'A+';
        elseif ($averagePercentage >= 85) $gradeLetter = 'A';
        elseif ($averagePercentage >= 80) $gradeLetter = 'A-';
        elseif ($averagePercentage >= 75) $gradeLetter = 'B+';
        elseif ($averagePercentage >= 70) $gradeLetter = 'B';
        elseif ($averagePercentage >= 65) $gradeLetter = 'C+';
        elseif ($averagePercentage >= 60) $gradeLetter = 'C';
        elseif ($averagePercentage >= 50) $gradeLetter = 'D';

        // 3. Fees Due (Unpaid / Partial)
        $unpaidFees = \App\Models\StudentFee::where('student_id', $studentId)
            ->whereIn('status', ['unpaid', 'partial'])
            ->with(['payments', 'feeStructure'])
            ->orderBy('due_date', 'asc')
            ->get();

        $feesDue = $unpaidFees->sum(function ($fee) {
            $invoiceTotal = $fee->amount + $fee->late_fee - $fee->discount;
            $paid = $fee->payments->sum('amount_paid');
            return max(0, $invoiceTotal - $paid);
        });

        // 4. Total Annual Fees (Generated)
        $totalAnnualFees = \App\Models\StudentFee::where('student_id', $studentId)
            ->whereYear('due_date', now()->year)
            ->get()
            ->sum(function ($fee) {
                return $fee->amount + $fee->late_fee - $fee->discount;
            });

        // 5. Total Paid This Year
        $totalPaidYearly = \App\Models\FeePayment::whereHas('studentFee', function ($q) use ($studentId) {
            $q->where('student_id', $studentId);
        })
            ->whereYear('payment_date', now()->year)
            ->sum('amount_paid');

        return view('profile', compact('student', 'attendanceRate', 'averagePercentage', 'gradeLetter', 'feesDue', 'unpaidFees', 'totalAnnualFees', 'totalPaidYearly'));
    }


    // ✅ Update profile
    public function updateProfile(Request $request)
    {
        $student = \App\Models\Student::find(session('student_id'));

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'department' => 'required|string',
        ]);

        $student->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'department' => $request->department,
        ]);

        return redirect()->route('student.profile')->with('success', 'Profile updated successfully!');
    }
}
