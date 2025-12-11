<?php

namespace App\Console\Commands;

use App\Jobs\ExpireSubscriptionsJob;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class ExpireSubscriptionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch job to expire user subscriptions that have reached their end date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Dispatching subscription expiration job...');

        ExpireSubscriptionsJob::dispatch();

        $this->info('Subscription expiration job dispatched successfully.');
        return SymfonyCommand::SUCCESS;
    }
}
