<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentFee;

class SmsService
{
    /**
     * Send Consolidated Fee Notification
     * 
     * Scenario:
     * "Dear Parent, Fees generated for Jan. Ali: Rs 6000 Sara: Rs 3500 Total Family Due: Rs 9500. View details on Portal: [Link]"
     * 
     * @param array $studentIds  List of student IDs for whom fees were generated
     * @param string $month      e.g. "2025-01"
     */
    public function sendConsolidatedFeeNotification(array $studentIds, $month)
    {
        // 1. Fetch Students with their Parents' Number
        $students = Student::whereIn('id', $studentIds)
            ->whereNotNull('parent_phone')
            ->get()
            ->groupBy('parent_phone');

        $allStudentIds = collect($students)->flatten()->pluck('id')->toArray();
        $feesByStudent = StudentFee::whereIn('student_id', $allStudentIds)
            ->where('month', $month)
            ->get()
            ->groupBy('student_id');

        foreach ($students as $parentPhone => $siblings) {
            $messageParts = [];
            $totalFamilyDue = 0;
            $monthName = date('M', strtotime($month));

            $messageParts[] = "Dear Parent, Fees generated for $monthName.";

            foreach ($siblings as $child) {
                // Calculate Total Fee for this child for this month
                // Fetched from memory map to prevent N+1 queries
                $childTotal = isset($feesByStudent[$child->id]) ? $feesByStudent[$child->id]->sum('amount') : 0;

                if ($childTotal > 0) {
                    $messageParts[] = "{$child->name}: Rs {$childTotal}";
                    $totalFamilyDue += $childTotal;
                }
            }

            if ($totalFamilyDue > 0) {
                $messageParts[] = "Total Family Due: Rs {$totalFamilyDue}.";
                $messageParts[] = "View details on Portal: " . route('login'); // or route('parent.dashboard')

                $finalMessage = implode(' ', $messageParts);

                // MOCK SMS SENDING
                // In production: SmsGateway::send($parentPhone, $finalMessage);
                \Illuminate\Support\Facades\Log::info("SMS SENT to {$parentPhone}: " . $finalMessage);
            }
        }
    }

    /**
     * Send Student Report Alert (Escalation)
     */
    public function sendStudentReportAlert(\App\Models\StudentReport $report)
    {
        $student = $report->student;
        if (!$student || !$student->parent_phone) {
            return;
        }

        $message = "Alert: A complaint regarding {$student->name} has been issued. Severity: " . ucfirst($report->severity) . ". Please check portal for details.";

        // MOCK SMS/WhatsApp SENDING
        \Illuminate\Support\Facades\Log::info("ALERT SENT to {$student->parent_phone}: " . $message);

        // Mock Email
        // Mail::to($student->email)->send(new ReportEscalated($report));
        \Illuminate\Support\Facades\Log::info("EMAIL SENT to parent of {$student->name}: Report Escalated.");
    }
}
