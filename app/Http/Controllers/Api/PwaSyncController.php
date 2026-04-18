<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\StudentFee;
use Illuminate\Support\Facades\Log;

class PwaSyncController extends Controller
{
    public function syncAttendance(Request $request)
    {
        try {
            // Validate incoming data
            $request->validate([
                'id' => 'required', // The unique ID from IndexedDB
                'student_id' => 'required|exists:students,id',
                'status' => 'required|string',
                'school_class_id' => 'required|exists:school_classes,id',
                'date' => 'required|date',
                // Optional context usually needed depending on who is syncing
                'teacher_id' => 'nullable|exists:teachers,id',
            ]);

            // For PWA syncs, we usually assume the logged in user is making the request
            $schoolId = null;
            if (auth()->guard('teacher')->check()) {
                $schoolId = auth()->guard('teacher')->user()->school_id;
            } elseif (auth()->guard('web')->check()) {
                $schoolId = auth()->guard('web')->user()->school_id; // Using school_id from admin
            } else {
                 return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $attendance = Attendance::updateOrCreate(
                [
                    'student_id' => $request->student_id,
                    'date' => $request->date,
                ],
                [
                    'school_class_id' => $request->school_class_id,
                    'status' => $request->status,
                    'teacher_id' => $request->teacher_id ?? auth()->guard('teacher')->id(),
                    'school_id' => $schoolId
                ]
            );

            Log::channel('sms')->info("PWA Background Sync - Attendance synced for student: {$request->student_id}");

            return response()->json([
                'success' => true,
                'message' => 'Attendance synced successfully',
                'attendance_id' => $attendance->id
            ]);

        } catch (\Exception $e) {
            Log::error("PWA Sync Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Sync failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function syncFees(Request $request)
    {
        try {
            // 1. Validate incoming data
            $request->validate([
                'transaction_id' => 'required|string',
                'student_id' => 'required|exists:students,id',
                'fee_structure_id' => 'required|exists:fee_structures,id',
                'month' => 'required',
                'due_date' => 'required|date',
                'base_amount' => 'required|numeric|min:0',
                'admission_fee' => 'nullable|numeric|min:0',
                'exam_fee' => 'nullable|numeric|min:0',
                'transport_fee' => 'nullable|numeric|min:0',
                'late_fee' => 'nullable|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'note' => 'nullable|string',
            ]);

            // 2. Auth & School ID Check
            $schoolId = null;
            if (auth()->guard('web')->check()) {
                $schoolId = auth()->guard('web')->user()->school_id;
            } else {
                 return response()->json(['success' => false, 'message' => 'Unauthorized or session expired'], 401);
            }

            // 3. Validate Data Integrity Hash
            if ($request->has('validation_hash')) {
                $hashSource = $request->student_id . '|' . $request->base_amount . '|' . $request->month;
                $expectedHash = hash('sha256', $hashSource);
                
                if ($request->validation_hash !== $expectedHash) {
                    Log::channel('sms')->warning("PWA Sync - Integrity Hash Mismatch for transaction: {$request->transaction_id}");
                    return response()->json(['success' => false, 'message' => 'Data integrity validation failed'], 400);
                }
            }

            // 3. Double-check uniqueness manually (Composite Safety Check)
            $existing = StudentFee::where('transaction_id', $request->transaction_id)->first();
            if ($existing) {
                return response()->json([
                    'success' => true,
                    'message' => 'Record already exists (Sync redundancy check)',
                    'fee_id' => $existing->id
                ]);
            }

            // 4. Create/Update using updateOrCreate
            $invoiceNo = 'INV-' . strtoupper(uniqid());
            
            $fee = StudentFee::updateOrCreate(
                ['transaction_id' => $request->transaction_id],
                [
                    'student_id' => $request->student_id,
                    'fee_structure_id' => $request->fee_structure_id,
                    'invoice_no' => $invoiceNo,
                    'month' => $request->month,
                    'amount' => $request->base_amount,
                    'due_date' => $request->due_date,
                    'status' => 'unpaid',
                    'admission_fee' => $request->admission_fee ?? 0,
                    'exam_fee' => $request->exam_fee ?? 0,
                    'transport_fee' => $request->transport_fee ?? 0,
                    'late_fee' => $request->late_fee ?? 0,
                    'discount' => $request->discount ?? 0,
                    'note' => $request->note,
                    'school_id' => $schoolId,
                ]
            );

            Log::channel('sms')->info("PWA Background Sync - Fee record synced: {$request->transaction_id} for student: {$request->student_id}");

            return response()->json([
                'success' => true,
                'message' => 'Fee record synced successfully',
                'fee_id' => $fee->id
            ]);

        } catch (\Exception $e) {
            Log::error("PWA Fee Sync Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Fee sync failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
