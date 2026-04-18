<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StudentFee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ApplyLateFines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fees:apply-late-fines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically apply late fines to overdue student fees.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        $this->info("Checking for overdue fees on {$today->toDateString()}...");

        // Get all unpaid or partially paid fees with due dates in the past
        $overdueFees = StudentFee::whereIn('status', ['unpaid', 'partial'])
            ->where('due_date', '<', $today)
            ->get();

        $updatedCount = 0;

        foreach ($overdueFees as $fee) {
            $dueDate = Carbon::parse($fee->due_date);
            $daysLate = $today->diffInDays($dueDate);

            $newLateFee = 0;

            // Logic:
            // Due Date passed -> 50 Rs fine
            // Due Date passed by 10 days -> 100 Rs fine

            if ($daysLate > 10) {
                $newLateFee = 100;
            } elseif ($daysLate > 0) {
                $newLateFee = 50;
            }

            // Only update if the logic dictates a higher fine than what's already there
            // Or if we strictly follow it as a fixed late fee tiered system.
            if ($fee->late_fee != $newLateFee) {
                // If manual late fee was added, do we override it? 
                // Let's only apply if it increases the late fee (so we don't overwrite custom higher fines).
                if ($newLateFee > $fee->late_fee) {
                    $fee->late_fee = $newLateFee;
                    $fee->save();
                    $updatedCount++;
                }
            }
        }

        $this->info("Late fines applied to {$updatedCount} fee records.");
        Log::info("Late fines applied to {$updatedCount} fee records.");
    }
}
