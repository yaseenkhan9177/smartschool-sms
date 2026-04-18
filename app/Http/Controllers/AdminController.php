<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;

class AdminController extends Controller
{
    // Dashboard showing stats and recent students/teachers
    public function dashboard()
    {
        // 1. Counter Cards
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalCourses = 5; // Placeholder - Optional to remove if unused
        $totalClasses = SchoolClass::count();

        $todaysCollection = \App\Models\FeePayment::whereDate('payment_date', \Carbon\Carbon::today())->sum('amount_paid');
        $monthlyCollection = \App\Models\FeePayment::whereYear('payment_date', \Carbon\Carbon::now()->year)
            ->whereMonth('payment_date', \Carbon\Carbon::now()->month)
            ->sum('amount_paid');
        $totalRevenue = \App\Models\FeePayment::sum('amount_paid');
        // Calculate pending fees using DB-level math to avoid loading all records into memory
        $paidSoFar = \Illuminate\Support\Facades\DB::table('fee_payments')
            ->join('student_fees', 'fee_payments.student_fee_id', '=', 'student_fees.id')
            ->whereIn('student_fees.status', ['unpaid', 'partial'])
            ->sum('fee_payments.amount_paid');
        $totalPendingFees = \App\Models\StudentFee::whereIn('status', ['unpaid', 'partial'])
            ->selectRaw('SUM(amount + late_fee - discount) as gross')
            ->value('gross') - $paidSoFar;

        $totalExpenses = \App\Models\Expense::sum('amount');
        $monthlyExpenses = \App\Models\Expense::whereYear('expense_date', \Carbon\Carbon::now()->year)
            ->whereMonth('expense_date', \Carbon\Carbon::now()->month)
            ->sum('amount');

        // 2. Financial Chart (Income vs Expenses) - Last 12 months
        $months = collect(range(11, 0))->map(function ($i) {
            return \Carbon\Carbon::today()->subMonths($i)->format('M');
        });

        // Build chart data for last 12 months using 2 queries instead of 24
        $incomeRaw = \App\Models\FeePayment::selectRaw("DATE_FORMAT(payment_date, '%Y-%m') as ym, SUM(amount_paid) as total")
            ->where('payment_date', '>=', \Carbon\Carbon::today()->subMonths(11)->startOfMonth())
            ->groupBy('ym')->pluck('total', 'ym');

        $expenseRaw = \App\Models\Expense::selectRaw("DATE_FORMAT(expense_date, '%Y-%m') as ym, SUM(amount) as total")
            ->where('expense_date', '>=', \Carbon\Carbon::today()->subMonths(11)->startOfMonth())
            ->groupBy('ym')->pluck('total', 'ym');

        $incomeData = collect(range(11, 0))->map(function ($i) use ($incomeRaw) {
            $key = \Carbon\Carbon::today()->subMonths($i)->format('Y-m');
            return $incomeRaw[$key] ?? 0;
        });

        $expenseData = collect(range(11, 0))->map(function ($i) use ($expenseRaw) {
            $key = \Carbon\Carbon::today()->subMonths($i)->format('Y-m');
            return $expenseRaw[$key] ?? 0;
        });

        // 3. Live Attendance (Students)
        $today = \Carbon\Carbon::today();
        $totalStudentsPresent = \App\Models\Attendance::whereDate('date', $today)->where('status', 'present')->count();
        $totalStudentsAbsent = \App\Models\Attendance::whereDate('date', $today)->where('status', 'absent')->count();

        // 4. Live Attendance (Teachers)
        $totalTeachersPresent = \App\Models\TeacherAttendance::whereDate('attendance_date', $today)->where('status', 'present')->count();
        $totalTeachersAbsent = \App\Models\TeacherAttendance::whereDate('attendance_date', $today)->where('status', 'absent')->count();

        // 5. Recent Activity
        $recentActivities = \App\Models\ActivityLog::latest()->take(10)->get();

        // 6. Upcoming Events (for Admin)
        $upcomingEvents = \App\Models\Event::whereJsonContains('target_audience', 'admin')
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->take(3)
            ->get();
        // Keep recent students/teachers if needed elsewhere, but user asked to replace "Recent Students" card.
        // We'll keep them in controller for now just in case, or replace.
        // Let's replace $recentStudents with $recentActivities in the variables passed to view.
        $recentStudents = Student::latest()->take(5)->get(); // Keeping it for safety if view relies on it elsewhere? 
        // Actually, let's just add $recentActivities to the compact.
        $recentTeachers = Teacher::latest()->take(5)->get();
        $pendingStudentApprovals = Student::where('status', 'pending')->count();

        // 6. Defaulter List (Top 10)
        $defaulters = \App\Models\StudentFee::where('status', '!=', 'paid')
            ->with(['student.schoolClass'])
            ->orderBy('amount', 'desc')
            ->take(10)
            ->get();

        // 7. Daily Financial Data (for "This Month" view)
        $daysInMonth = \Carbon\Carbon::now()->daysInMonth;

        // 8. Pending Reports Count (Alert)
        $pendingReportsCount = \App\Models\StudentReport::where('status', 'pending')->count();

        $currentMonthDays = collect(range(1, $daysInMonth))->map(function ($i) {
            return \Carbon\Carbon::createFromDate(now()->year, now()->month, $i)->format('d M');
        });

        $adminId = \Illuminate\Support\Facades\Auth::id();
        $monthStart = \Carbon\Carbon::now()->startOfMonth()->toDateString();
        $monthEnd   = \Carbon\Carbon::now()->endOfMonth()->toDateString();

        // 2 queries instead of 62 (31 days x 2)
        $dailyIncomeRaw = \App\Models\FeePayment::where('school_id', $adminId)
            ->selectRaw('DAY(payment_date) as day, SUM(amount_paid) as total')
            ->whereBetween('payment_date', [$monthStart, $monthEnd])
            ->groupBy('day')->pluck('total', 'day');

        $dailyExpenseRaw = \App\Models\Expense::where('school_id', $adminId)
            ->selectRaw('DAY(expense_date) as day, SUM(amount) as total')
            ->whereBetween('expense_date', [$monthStart, $monthEnd])
            ->groupBy('day')->pluck('total', 'day');

        $dailyIncomeData = collect(range(1, $daysInMonth))->map(fn($i) => $dailyIncomeRaw[$i] ?? 0);
        $dailyExpenseData = collect(range(1, $daysInMonth))->map(fn($i) => $dailyExpenseRaw[$i] ?? 0);

        // Pass all data to view
        return view('school admin.dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalCourses',
            'totalClasses', // Renamed from activeSemesters
            'todaysCollection',
            'monthlyCollection', // Added
            'totalPendingFees',
            'months',
            'incomeData',
            'expenseData',
            'totalStudentsPresent',
            'totalStudentsAbsent',
            'totalTeachersPresent',
            'totalTeachersAbsent',
            'recentStudents',
            'recentTeachers',
            'pendingStudentApprovals',
            'defaulters',
            'currentMonthDays',
            'dailyIncomeData',
            'dailyIncomeData',
            'dailyExpenseData',
            'recentActivities',
            'upcomingEvents',
            'pendingReportsCount',
            'totalExpenses',
            'monthlyExpenses'
        ));
    }

    // Manage all students
    public function manageStudents(\Illuminate\Http\Request $request)
    {
        $query = Student::with('schoolClass')->latest();
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }
        $students = $query->paginate(10);
        return view('school admin.manage_students', compact('students'));
    }

    // Create student form
    public function createStudent()
    {
        $classes = SchoolClass::orderBy('name')->get(['id', 'name']);
        $transportRoutes = \App\Models\TransportRoute::where('status', 'active')->get();
        return view('school admin.create_student', compact('classes', 'transportRoutes'));
    }

    // Approve student
    public function approveStudent($id)
    {
        $student = Student::findOrFail($id);
        $student->status = 'approved';
        $student->save();
        return redirect()->back()->with('success', 'Student approved successfully.');
    }

    // Edit student
    public function editStudent($id)
    {
        $student = Student::findOrFail($id);
        return view('school admin.edit_student', compact('student'));
    }

    // Update student
    public function updateStudent(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'parent_phone' => 'required|digits:11',
            // 'class_id' => 'required', // Add validation if editable
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'transport_required' => 'nullable|in:yes,no',
            'transport_fee' => 'required_if:transport_required,yes|nullable|numeric|min:0',
        ]);

        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->parent_phone = $request->parent_phone;
        // $student->class_id = $request->class_id; 

        $transportFee = 0;
        if ($request->input('transport_required') === 'yes') {
            $transportFee = $request->input('transport_fee', 0);
        }
        $student->transport_fee = $transportFee;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/students'), $filename);
            $student->profile_image = $filename;
        }

        $student->save();

        return redirect()->route('admin.students')->with('success', 'Student updated successfully');
    }

    // Show student profile and fee history
    public function showStudent($id)
    {
        $student = Student::with('schoolClass')->findOrFail($id);

        // Get last 12 months fee history
        $twelveMonthsAgo = \Carbon\Carbon::now()->subMonths(12)->startOfMonth()->format('Y-m');

        $feeHistory = \App\Models\StudentFee::with('payments', 'feeStructure.feeCategory')
            ->where('student_id', $id)
            ->where('month', '>=', $twelveMonthsAgo)
            ->orderBy('month', 'desc')
            ->get();

        $totalPaid = 0;
        $totalPending = 0;

        foreach ($feeHistory as $fee) {
            $paid = $fee->payments->sum('amount_paid');
            $totalPaid += $paid;

            $payable = ($fee->amount + $fee->late_fee) - $fee->discount;
            if ($fee->status !== 'paid') {
                $totalPending += max(0, $payable - $paid);
            }
        }

        // We can group them by invoice or just show a flat list. 
        // Grouping by invoice is better for the view.
        $invoices = $feeHistory->groupBy('invoice_no');

        return view('school admin.students.show', compact('student', 'invoices', 'totalPaid', 'totalPending'));
    }

    // Delete student
    public function deleteStudent($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return redirect()->route('admin.students')->with('success', 'Student deleted successfully');
    }

    // Create teacher form
    public function createTeacher()
    {
        $subjects = \App\Models\Subject::orderBy('name')->get(['id', 'name']);
        $classes = SchoolClass::orderBy('name')->get(['id', 'name']);
        return view('school admin.create_teacher', compact('subjects', 'classes'));
    }
    // Store new teacher
    public function storeTeacher(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:teachers,email',
            'password' => 'required|min:6',
            'subject' => 'required',
            'education_level' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'classes' => 'nullable|array',
            'classes.*' => 'exists:school_classes,id'
        ]);

        $schoolId = \Illuminate\Support\Facades\Auth::id();

        // Custom Teacher ID Generation
        // Format: {school_id}0{sequence}
        // Sequence: 101 - 199

        $lastTeacher = Teacher::where('school_id', $schoolId)->latest('id')->first();
        $sequence = 101;

        if ($lastTeacher) {
            // Attempt to parse existing ID
            $prefix = $schoolId . '0';
            // Cast ID to string for string functions
            $lastIdStr = (string)$lastTeacher->id;

            if (strpos($lastIdStr, $prefix) === 0) {
                $lastSequence = (int) substr($lastIdStr, strlen($prefix));
                $sequence = $lastSequence + 1;
            }
        }

        if ($sequence > 199) {
            return redirect()->back()->withErrors(['error' => 'Teacher Capacity Full (Max 199 per school)']);
        }

        $newId = (int)($schoolId . '0' . $sequence);

        $teacher = new Teacher();
        $teacher->id = $newId; // Manually assign ID
        $teacher->school_id = $schoolId;
        $teacher->name = $request->name;
        $teacher->email = $request->email;
        $teacher->password = bcrypt($request->password);
        $teacher->subject = $request->subject;
        $teacher->education_level = $request->education_level;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $teacher->image = $filename;
        }

        $teacher->save();

        if ($request->has('classes')) {
            $teacher->schoolClasses()->attach($request->classes);
        }

        return redirect()->route('admin.teachers')->with('success', 'Teacher registered successfully. ID: ' . $newId);
    }
    // Store new teacher


    // Manage all teachers
    public function manageTeachers(\Illuminate\Http\Request $request)
    {
        $query = Teacher::with('schoolClasses')->latest();
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('subject', 'like', "%{$search}%");
        }
        $teachers = $query->paginate(10);
        return view('school admin.manage_teachers', compact('teachers'));
    }

    // Edit teacher
    public function editTeacher($id)
    {
        $teacher = Teacher::findOrFail($id);
        $subjects = \App\Models\Subject::orderBy('name')->get(['id', 'name']);
        $classes = SchoolClass::orderBy('name')->get(['id', 'name']);
        return view('school admin.edit_teacher', compact('teacher', 'subjects', 'classes'));
    }

    // Update teacher
    public function updateTeacher(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'education_level' => 'required',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'classes' => 'nullable|array',
            'classes.*' => 'exists:school_classes,id'
        ]);

        $teacher->name = $request->name;
        $teacher->email = $request->email;
        if ($request->filled('password')) {
            $teacher->password = bcrypt($request->password);
        }
        $teacher->subject = $request->subject;
        $teacher->education_level = $request->education_level;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $teacher->image = $filename;
        }

        $teacher->save();

        if ($request->has('classes')) {
            $teacher->schoolClasses()->sync($request->classes);
        } else {
            $teacher->schoolClasses()->detach();
        }

        return redirect()->route('admin.teachers')->with('success', 'Teacher updated successfully');
    }

    // Delete teacher
    public function deleteTeacher($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();
        return redirect()->route('admin.teachers')->with('success', 'Teacher deleted successfully');
    }

    // Show teacher profile
    public function showTeacher($id)
    {
        $teacher = Teacher::with('schoolClasses')->findOrFail($id);
        $timetables = \App\Models\Timetable::where('teacher_id', $id)
            ->with(['schoolClass', 'subject'])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('school admin.show_teacher', compact('teacher', 'timetables'));
    }

    // Manage all classes
    public function manageClasses()
    {
        $classes = SchoolClass::withCount('students')->paginate(15);
        return view('school admin.manage_classes', compact('classes'));
    }

    // Create class form
    public function createClass()
    {
        return view('school admin.create_class');
    }

    // Store new class
    public function storeClass(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:school_classes,name'
        ]);

        $class = new SchoolClass();
        $class->name = $request->name;
        $class->school_id = \Illuminate\Support\Facades\Auth::id();
        $class->save();

        return redirect()->route('admin.classes')->with('success', 'Class created successfully');
    }

    // Show class students
    public function showClassStudents($id)
    {
        $class = SchoolClass::findOrFail($id);
        $students = Student::where('class_id', $id)->paginate(50);
        return view('school admin.class_students', compact('class', 'students'));
    }

    // Show class timetable
    public function showClassTimetable($id)
    {
        $class = SchoolClass::findOrFail($id);
        $timetables = \App\Models\Timetable::where('school_class_id', $id)
            ->with(['teacher', 'subject'])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();
        return view('school admin.class_timetable', compact('class', 'timetables'));
    }

    // Manage Parents (Derived from Students)
    public function manageParents()
    {
        // Use SchoolParent model for better performance and pagination
        $parents = \App\Models\SchoolParent::with('students')->latest()->paginate(15);

        return view('school admin.parents', compact('parents'));
    }

    // Student Reports Management
    public function reports()
    {
        $reports = \App\Models\StudentReport::with(['student.schoolClass', 'teacherReporter', 'accountantReporter', 'adminReporter'])
            ->latest()
            ->paginate(20);
        return view('school admin.reports.index', compact('reports'));
    }

    public function updateReportStatus(Request $request, $id)
    {
        $report = \App\Models\StudentReport::findOrFail($id);

        $request->validate([
            'action' => 'required|in:resolve,escalate',
            'note' => 'nullable|string'
        ]);

        if ($request->action == 'resolve') {
            $report->status = 'resolved';
            $report->resolution_note = $request->note;
            $report->save();
            return back()->with('success', 'Report resolved internally.');
        } elseif ($request->action == 'escalate') {
            $report->status = 'escalated';
            $report->resolution_note = $request->note;
            $report->save();

            // Notify Parent
            $smsService = new \App\Services\SmsService();
            $smsService->sendStudentReportAlert($report);

            return back()->with('success', 'Report escalated to parent.');
        }

        return back();
    }
}
