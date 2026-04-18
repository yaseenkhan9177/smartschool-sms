<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        // Validation (copied from/aligned with AdminController logic assumption)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string',
            'password' => 'required|string|min:6',
            'parent_phone' => 'nullable|digits:11',
            'parent_email' => 'nullable|email',
            'gender' => 'required|in:male,female,other',
            'dob' => 'nullable|date',
            'transport_required' => 'nullable|in:yes,no',
            'transport_fee' => 'required_if:transport_required,yes|nullable|numeric|min:0',
            'transport_route_id' => 'required_if:transport_required,yes|nullable|exists:transport_routes,id',
        ]);

        $user = $request->user();
        // Fallback for different guards if not automatically resolved by request
        if (!$user) {
            if (Auth::guard('web')->check()) $user = Auth::guard('web')->user();
            elseif (Auth::guard('accountant')->check()) $user = Auth::guard('accountant')->user();
        }

        $schoolId = null;

        if ($user instanceof \App\Models\User && $user->role === 'admin') {
            $schoolId = $user->id;
        } elseif ($user instanceof \App\Models\Accountant) {
            $schoolId = $user->school_id;
        } elseif ($user instanceof \App\Models\Teacher) {
            $schoolId = $user->school_id;
        } else {
            // Fallback or error if unauthorized type attempts to create
            return redirect()->back()->withErrors(['error' => 'Unauthorized action.']);
        }

        // Custom Roll Number Generation
        // Format: {school_id}00{sequence}
        // Sequence starts at 1001. Max 1999.

        // Find last student for this school to determine sequence
        $lastStudent = Student::where('school_id', $schoolId)
            ->latest('id')
            ->first();

        $sequence = 1001;

        if ($lastStudent && $lastStudent->roll_number) {
            // Extract sequence from roll number
            // Assumption: Roll number format is rigidly {school_id}00{sequence}
            // Length of school_id varies, but we know the structure.
            // Better approach: Store sequence separately? No, user asked to parse/increment.
            // We can try to extract the last 4 digits.
            // If school_id = 5, roll = 5001001. 
            // school_id . '00' is the prefix.
            $prefix = $schoolId . '00';
            if (strpos($lastStudent->roll_number, $prefix) === 0) {
                $lastSequence = (int) substr($lastStudent->roll_number, strlen($prefix));
                $sequence = $lastSequence + 1;
            }
        }

        if ($sequence > 1999) {
            return redirect()->back()->withErrors(['error' => 'School Capacity Full (Max 1999 Students)']);
        }

        $rollNumber = $schoolId . '00' . $sequence;

        // Logic to Link or Create Parent
        $parentPhone = $request->parent_phone;
        $parentId = null;

        if ($parentPhone) {
            // Check if parent exists
            $parent = \App\Models\SchoolParent::where('phone', $parentPhone)->withoutGlobalScopes()->first();
            // used withoutGlobalScopes to find parent globally? No, phone should be unique per school or global? 
            // Schema: unique(['school_id', 'phone']). So specific to school.
            // We should search within school.
            $parent = \App\Models\SchoolParent::where('school_id', $schoolId)->where('phone', $parentPhone)->first();

            if ($parent) {
                $parentId = $parent->id;
                // Update existing parent email if provided and not set
                if (!$parent->email && $request->parent_email) {
                    $parent->email = $request->parent_email;
                    $parent->save();
                }
            } else {
                // Create new parent
                $newParent = new \App\Models\SchoolParent();
                $newParent->school_id = $schoolId;
                $newParent->name = $request->parent_name ?? 'Parent';
                $newParent->email = $request->parent_email; // Save Email
                $newParent->phone = $parentPhone;
                $newParent->password = Hash::make($parentPhone); // Phone as password
                $newParent->save();
                $parentId = $newParent->id;
            }
        }

        // =========================================================
        // FAMILY SYSTEM: Auto-find or create family by father email
        // =========================================================
        $familyId = null;
        $fatherEmail = $request->input('father_email', $request->input('parent_email')); // Fallback to parent_email

        // If a specific family_id was passed from the AJAX popup choice, use it
        if ($request->filled('family_id')) {
            $familyId = $request->input('family_id');
        } elseif ($fatherEmail) {
            $family = \App\Models\Family::where('email', $fatherEmail)
                ->where('school_id', $schoolId)
                ->first();

            if (!$family) {
                $family = \App\Models\Family::create([
                    'family_code' => \App\Models\Family::generateCode(),
                    'father_name' => $request->input('father_name', $request->input('parent_name', 'Guardian')),
                    'email'       => $fatherEmail,
                    'phone'       => $request->input('father_phone', $request->input('parent_phone', '')),
                    'address'     => $request->input('father_address'),
                    'school_id'   => $schoolId,
                ]);
            }

            $familyId = $family->id;
        }

        // Store transport fee in student table as simple reference (legacy/fallback)
        $transportFee = 0;
        if ($request->input('transport_required') === 'yes') {
            $transportFee = $request->input('transport_fee', 0);
        }


        $student = Student::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'gender'        => $request->gender,
            'dob'           => $request->dob,
            'password'      => Hash::make($request->password),
            'phone'         => $request->phone,
            'roll_number'   => $rollNumber,
            'school_id'     => $schoolId,
            'status'        => 'approved', // Auto-approve if created by Admin
            'parent_phone'  => $request->parent_phone,
            'parent_name'   => $request->parent_name, // Keep legacy for now
            'parent_id'     => $parentId,             // Link new ID
            'class_id'      => $request->class_id,
            'department'    => $request->department,
            'transport_fee' => $transportFee,
            'family_id'     => $familyId,             // Family System
        ]);


        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/students'), $filename);
            $student->profile_image = $filename;
            $student->save();
        }

        // Save Transport Details (New Table)
        if ($request->input('transport_required') === 'yes' && $request->transport_route_id) {
            $startMonth = $request->transport_start_month ? $request->transport_start_month . '-01' : now()->toDateString();

            \App\Models\StudentTransport::create([
                'student_id' => $student->id,
                'transport_route_id' => $request->transport_route_id,
                'pickup_point' => $request->pickup_point,
                'monthly_fee' => $request->transport_fee,
                'start_month' => $startMonth,
                'status' => 'active'
            ]);
        }

        if ($user instanceof \App\Models\Accountant) {
            return redirect()->route('accountant.students.index')->with('success', 'Student created successfully. Roll Number: ' . $rollNumber);
        }

        return redirect()->route('admin.students')->with('success', 'Student created successfully. Roll Number: ' . $rollNumber);
    }

    public function searchParent(Request $request)
    {
        $request->validate([
            'phone' => 'required|string'
        ]);

        $parent = \App\Models\SchoolParent::where('phone', $request->phone)->first();

        if ($parent) {
            return response()->json([
                'status' => 'success',
                'parent' => [
                    'name' => $parent->name,
                    'email' => $parent->email,
                    'id' => $parent->id
                ]
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Parent not found'
        ]);
    }
}
