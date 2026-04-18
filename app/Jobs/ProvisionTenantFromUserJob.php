<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\TenantService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProvisionTenantFromUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $timeout = 180;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(TenantService $tenantService): void
    {
        try {
            // 1. Get pre-generated DB Name
            $databaseName = $this->user->database_name;

            if (empty($databaseName)) {
                throw new \Exception("Database name is missing for user: " . $this->user->email);
            }

            // 2. Create DB & Config
            $tenantService->createTenantDatabase($databaseName);
            $tenantService->configureConnection($databaseName);

            // 3. Migrate and Seed (TenantService passes name/email/password via config)
            $tenantService->migrateAndSeedTenant($databaseName, $this->user->name, $this->user->email, $this->user->password);

            // 4. Update tracking in Master
            DB::connection('mysql')->transaction(function () use ($databaseName) {
                $this->user->database_name = $databaseName;
                $this->user->status = 'active';
                $this->user->save();
            });
        } catch (\Throwable $e) {
            Log::error('Tenant provisioning failed for user: ' . $this->user->email . ' - ' . $e->getMessage());

            if (isset($databaseName)) {
                $tenantService->dropTenantDatabase($databaseName);
            }

            $this->user->update(['status' => 'failed_provisioning']);

            throw $e;
        }
    }
}
