<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentTransport;
use App\Models\StudentFee;
use App\Models\FeeCategory;
use App\Models\FeeStructure;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransportFeeController extends Controller
{
    // Show Transport Fee Management Page (Optional, for manual generation)
    public function index()
    {
        // Maybe detail stats about transport collection
        return view('accountant.transport.index');
    }

    // Generate Fees for a specific month
    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m', // e.g. 2026-04
        ]);

        $month = $request->month;

        if (\Illuminate\Support\Facades\Auth::guard('accountant')->check()) {
            $schoolId = \Illuminate\Support\Facades\Auth::guard('accountant')->user()->school_id;
        } else {
            $schoolId = \Illuminate\Support\Facades\Auth::id();
        }

        // 1. Ensure "Transport" Fee Structure Exists
        $category = FeeCategory::firstOrCreate(
            ['name' => 'Transport', 'school_id' => $schoolId],
            ['description' => 'Transport Fees']
        );

        $structure = FeeStructure::firstOrCreate(
            [
                'fee_category_id' => $category->id,
                'school_id' => $schoolId,
                'class_id' => null
            ],
            [
                'amount' => 0,
                'academic_year' => date('Y') . '-' . (date('Y') + 1)
            ]
        );

        // 2. Get Eligible Students
        // Active status, and start_month is before or equal to the generation month
        $date = Carbon::createFromFormat('Y-m', $month)->startOfMonth();

        $transports = StudentTransport::where('status', 'active')
            ->whereDate('start_month', '<=', $date)
            ->whereHas('student', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->with('student')
            ->get();

        $count = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            foreach ($transports as $transport) {
                // Check if fee already exists for this month
                $exists = StudentFee::where('student_id', $transport->student_id)
                    ->where('fee_structure_id', $structure->id)
                    ->where('month', $month)
                    ->exists();

                if (!$exists) {
                    StudentFee::create([
                        'student_id' => $transport->student_id,
                        'fee_structure_id' => $structure->id,
                        'month' => $month,
                        'amount' => $transport->monthly_fee, // Use the specific fee from student_transport
                        'due_date' => $date->copy()->addDays(10), // Due by 10th
                        'status' => 'unpaid',
                        'school_id' => $schoolId,
                        // 'invoice_no' => 'TR-'.$transport->student_id.'-'.str_replace('-', '', $month) // removed from model earlier? let's check migration/model
                    ]);
                    $count++;
                } else {
                    $skipped++;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to generate fees: ' . $e->getMessage()]);
        }

        return redirect()->back()->with('success', "Generated Transport Fees for $count students. ($skipped already existed).");
    }
}
