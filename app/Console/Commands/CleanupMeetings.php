<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TeacherMeeting;
use App\Services\ZoomService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CleanupMeetings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meetings:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'End past meetings and remove them from Zoom to free up resources.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ZoomService $zoomService)
    {
        $this->info('Scanning for past meetings...');

        // Find meetings that started more than (duration + 4 hours) ago and are not yet 'ended'
        // We add a generous buffer (4 hours) to ensure we don't kill a meeting that started late or went long.
        $cutOffTime = Carbon::now()->subHours(4); // Simple cutoff for now

        $meetings = TeacherMeeting::where('start_time', '<', $cutOffTime)
            ->where('status', '!=', 'ended')
            ->get();

        $count = 0;

        foreach ($meetings as $meeting) {
            $this->info("Processing Meeting ID: {$meeting->id} - {$meeting->topic}");

            // 1. Delete from Zoom
            if ($meeting->zoom_meeting_id) {
                try {
                    $deleted = $zoomService->deleteMeeting($meeting->zoom_meeting_id);
                    if ($deleted) {
                        $this->info(" - Removed from Zoom.");
                    } else {
                        $this->warn(" - Failed to remove from Zoom (or already gone).");
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to delete Zoom meeting {$meeting->id}: " . $e->getMessage());
                    $this->error(" - Error removing from Zoom.");
                }
            }

            // 2. Mark as Ended in DB (Keep the record)
            $meeting->update(['status' => 'ended']);
            $this->info(" - Status updated to 'ended'.");

            $count++;
        }

        $this->info("Cleanup complete. Processed {$count} meetings.");
        return 0;
    }
}
