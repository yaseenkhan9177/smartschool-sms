<?php

namespace App\Http\Controllers;

use App\Models\StudentFee;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FeeReportController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::all();

        // Base Query
        $query = StudentFee::with(['student.schoolClass', 'feeStructure.feeCategory', 'payments']);

        // Filters
        if ($request->has('class_id') && $request->class_id) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->has('month') && $request->month) {
            $query->where('month', $request->month);
        }

        if ($request->has('status') && $request->status) {
            if ($request->status === 'pending') {
                $query->whereIn('status', ['unpaid', 'partial']);
            } elseif ($request->status === 'defaulter') {
                // Defaulter: Unpaid for more than 30 days
                $query->whereIn('status', ['unpaid', 'partial'])
                    ->where('due_date', '<', Carbon::now()->subDays(30));
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('roll_number', 'like', "%{$search}%");
            });
        }

        // CLONE BEFORE PAGINATION is crucial for aggregates
        $summaryQuery = clone $query;
        $reports = $query->orderBy('due_date', 'desc')->paginate(20);

        // Efficient Aggregation using subqueries to avoid loading collections into memory
        $totalAmount = $summaryQuery->sum(DB::raw('amount + admission_fee + exam_fee + transport_fee + late_fee - discount'));

        // Use join instead of whereIn with subquery to avoid MariaDB LIMIT issues completely
        $totalPaid = \App\Models\FeePayment::joinSub($summaryQuery->select('id'), 'filtered_fees', function ($join) {
            $join->on('fee_payments.student_fee_id', '=', 'filtered_fees.id');
        })->sum('fee_payments.amount_paid');

        $totalPending = max(0, $totalAmount - $totalPaid);

        // Defaulters Count (Unpaid > 30 days)
        $defaultersQuery = clone $summaryQuery;
        $defaultersCount = $defaultersQuery->whereIn('status', ['unpaid', 'partial'])
            ->where('due_date', '<', Carbon::now()->subDays(30))
            ->count();

        return view('admin.fees.reports.index', compact(
            'reports',
            'classes',
            'totalAmount',
            'totalPaid',
            'totalPending',
            'defaultersCount'
        ));
    }
}
