<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentFee;
use Illuminate\Support\Facades\Auth;

class StudentFeeViewController extends Controller
{
    public function index()
    {
        $studentId = session('student_id');
        // Fetch all fees
        $rawFees = StudentFee::where('student_id', $studentId)
            ->with(['feeStructure.feeCategory', 'payments'])
            ->latest('due_date')
            ->get();

        // Group by Invoice Number (or fallbacks for null invoice_no)
        $invoices = $rawFees->groupBy(function ($item) {
            return $item->invoice_no ?? ('Uninvoice-' . $item->month . '-' . $item->due_date);
        })->map(function ($group) {
            $first = $group->first();

            // Calculate Aggregates for the Group
            $totalAmount = $group->sum('amount');
            $totalLate = $group->sum('late_fee');
            $totalDiscount = $group->sum('discount');
            $totalPaid = $group->flatMap->payments->sum('amount_paid');

            $netPayable = ($totalAmount + $totalLate - $totalDiscount);
            $balance = max(0, $netPayable - $totalPaid);

            // Determine Status
            $status = 'unpaid';
            $statuses = $group->pluck('status')->unique();

            if ($totalPaid >= $netPayable - 1) { // 1 rupee tolerance
                $status = 'paid';
            } elseif ($totalPaid > 0) {
                $status = 'partial';
            } elseif ($statuses->contains('partial')) {
                $status = 'partial';
            } else {
                $status = 'unpaid';
            }

            // Check for Overdue
            if ($status !== 'paid' && \Carbon\Carbon::parse($first->due_date)->endOfDay()->isPast()) {
                $status = 'overdue';
            }

            return (object) [
                'invoice_no' => $first->invoice_no,
                'month' => $first->month,
                'due_date' => $first->due_date,
                'status' => $status,
                'total_amount' => $totalAmount,
                'total_late_fee' => $totalLate,
                'total_discount' => $totalDiscount,
                'total_paid' => $totalPaid,
                'net_payable' => $netPayable,
                'balance' => $balance,
                'items' => $group, // The individual fee items
                'id' => $first->id, // For linking
            ];
        });

        $invoices = $invoices->sortByDesc('due_date');

        // Calculate Summary Cards Data
        $totalOutstanding = $invoices->where('status', '!=', 'paid')->sum('balance');

        $totalPayableYear = $invoices->filter(function ($inv) {
            return \Carbon\Carbon::parse($inv->due_date)->year == now()->year;
        })->sum('net_payable');

        $totalPaidYear = $rawFees->flatMap->payments->filter(function ($p) {
            return \Carbon\Carbon::parse($p->payment_date)->year == now()->year;
        })->sum('amount_paid');

        $nextDueDate = $invoices->whereIn('status', ['unpaid', 'partial', 'overdue'])
            ->sortBy('due_date')
            ->first()
            ?->due_date;

        $payments = \App\Models\FeePayment::whereHas('studentFee', function ($q) use ($studentId) {
            $q->where('student_id', $studentId);
        })
            ->with('studentFee.feeStructure.feeCategory')
            ->latest('payment_date')
            ->get();

        return view('student.fees.index', compact('invoices', 'payments', 'totalOutstanding', 'totalPaidYear', 'nextDueDate', 'totalPayableYear'));
    }

    public function invoice($id)
    {
        $studentId = session('student_id');

        $fee = StudentFee::where('student_id', $studentId)
            ->with(['student.schoolClass', 'feeStructure.feeCategory', 'payments'])
            ->findOrFail($id);

        // Calculate totals for display if needed specifically for view logic
        // But the view handles it.

        if ($fee->invoice_no) {
            $fees = StudentFee::where('invoice_no', $fee->invoice_no)
                ->where('student_id', $studentId) // Ensure only own fees
                ->with(['student.school', 'student.schoolClass', 'feeStructure.feeCategory', 'payments'])
                ->get();
        } else {
            // Fallback for old fees without invoice number
            $fees = StudentFee::where('id', $id)
                ->where('student_id', $studentId)
                ->with(['student.school', 'student.schoolClass', 'feeStructure.feeCategory', 'payments'])
                ->get();
        }

        return view('accountant.fees.invoice', ['fee' => $fees->first(), 'fees' => $fees]);
    }
}
