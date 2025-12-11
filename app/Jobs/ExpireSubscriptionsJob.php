<?php

namespace App\Jobs;

use App\Models\UserSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExpireSubscriptionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Starting subscription expiration job...');

        // Find all active subscriptions that have passed their end date
        $count = UserSubscription::where('status', 'active')
            ->where('end_date', '<=', now())
            ->update(['status' => 'expired']);

        if ($count > 0) {
            Log::info("Successfully expired {$count} subscription(s).");
        } else {
            Log::info('No subscriptions to expire.');
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Subscription expiration job failed', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
