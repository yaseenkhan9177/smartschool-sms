<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunLicenseRenewalCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'license:check-renewals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expiring licenses and generate draft renewals';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();

        // 1. Mark expired licenses
        $expiredCount = \App\Models\LicenseKey::where('status', 'active')
            ->where('expiry_date', '<', $today)
            ->update(['status' => 'expired']);

        if ($expiredCount > 0) {
            $this->info("Marked $expiredCount licenses as expired.");
        }

        // 2. Suspend schools with expired licenses and no active license
        // (This might be redundant if middleware checks active licenses, but good for data consistency)
        // For now, middleware does the blocking. We can update user status if needed.

        // 3. Auto-Generate Draft Keys for licenses expiring soon (e.g. within 7 days)
        $sevenDaysFromNow = now()->addDays(7)->toDateString();

        $expiringLicenses = \App\Models\LicenseKey::where('status', 'active')
            ->where('expiry_date', '<=', $sevenDaysFromNow)
            ->where('expiry_date', '>=', $today)
            ->get();

        foreach ($expiringLicenses as $currentLicense) {
            // Check if a future key already exists
            $hasFutureKey = \App\Models\LicenseKey::where('school_id', $currentLicense->school_id)
                ->where('start_date', '>', $currentLicense->expiry_date)
                ->exists();

            if (!$hasFutureKey) {
                // Generate Draft
                $key = strtoupper(implode('-', str_split(bin2hex(random_bytes(8)), 4)));

                $startDate = \Carbon\Carbon::parse($currentLicense->expiry_date)->addDay();
                $expiryDate = $startDate->copy();

                // Match previous duration
                switch ($currentLicense->plan_duration) {
                    case '1_week':
                        $expiryDate->addWeek();
                        break;
                    case '1_month':
                        $expiryDate->addMonth();
                        break;
                    case '6_months':
                        $expiryDate->addMonths(6);
                        break;
                    case '1_year':
                        $expiryDate->addYear();
                        break;
                    default:
                        $expiryDate->addMonth(); // Fallback
                }

                \App\Models\LicenseKey::create([
                    'school_id' => $currentLicense->school_id,
                    'license_key' => $key,
                    'plan_duration' => $currentLicense->plan_duration,
                    'start_date' => $startDate,
                    'expiry_date' => $expiryDate,
                    'status' => 'inactive',
                    'is_auto_generated' => true,
                ]);

                $this->info("Generated draft key for School ID: {$currentLicense->school_id}");
            }
        }

        $this->info('License renewal check completed.');
    }
}
