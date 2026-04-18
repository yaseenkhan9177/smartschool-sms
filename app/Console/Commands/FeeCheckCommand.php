<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FeeCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fees:check {--apply-late-fees : Whether to automatically apply late fees to overdue records}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for due fees and send reminders. Optionally apply late fees.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting fee check...');

        // 1. Upcoming Reminders (e.g., 3 days before due date)
        $reminderDate = now()->addDays(3)->toDateString();
        $upcomingFees = \App\Models\StudentFee::where('due_date', $reminderDate)
            ->where('status', '!=', 'paid')
            ->with('student')
            ->get();

        $this->info("Found {$upcomingFees->count()} fees due on {$reminderDate}. Sending reminders...");

        foreach ($upcomingFees as $fee) {
            if ($fee->student) {
                // Send Notification (Using Notification Facade or User method)
                $title = 'Upcoming Fee Payment';
                $message = "Reminder: Your fee of PKR {$fee->total_amount} is due on " . \Carbon\Carbon::parse($fee->due_date)->format('d M, Y') . ".";

                // Assuming we use the existing NotificationController logic or direct Notification facade
                // Since we don't have a direct User model for students (it's Student model), we may need to make Student Notifiable 
                // or just use the system notification structure we saw earlier.
                // The NotificationController uses a 'GeneralNotification' class.

                \Illuminate\Support\Facades\Notification::send($fee->student, new \App\Notifications\GeneralNotification(
                    $title,
                    $message,
                    'System',
                    'system',
                    null
                ));
            }
        }

        // 2. Overdue Fees
        $overdueFees = \App\Models\StudentFee::where('due_date', '<', now()->toDateString())
            ->where('status', '!=', 'paid')
            ->where('late_fee', 0) // Only if not already applied to avoid double penalty
            ->with('student')
            ->get();

        $this->info("Found {$overdueFees->count()} overdue fees.");

        $applyLateFee = $this->option('apply-late-fees');

        foreach ($overdueFees as $fee) {
            if ($fee->student) {
                if ($applyLateFee) {
                    $lateFeeAmount = 100; // Fixed amount for now
                    $fee->update(['late_fee' => $lateFeeAmount]);

                    $title = 'Late Fee Applied';
                    $message = "Your fee payment is overdue. A late fee of PKR {$lateFeeAmount} has been applied. Total due: PKR {$fee->total_amount}.";
                } else {
                    $title = 'Fee Payment Overdue';
                    $message = "Your fee payment was due on " . \Carbon\Carbon::parse($fee->due_date)->format('d M, Y') . ". Please pay immediately to avoid penalties.";
                }

                \Illuminate\Support\Facades\Notification::send($fee->student, new \App\Notifications\GeneralNotification(
                    $title,
                    $message,
                    'System',
                    'system',
                    null
                ));
            }
        }

        $this->info('Fee check completed.');
    }
}
