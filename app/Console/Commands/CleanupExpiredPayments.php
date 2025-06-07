<?php

namespace App\Console\Commands;

use App\Models\Payment;
use Illuminate\Console\Command;

class CleanupExpiredPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:cleanup-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark payments as expired if they are older than 5 minutes and still pending';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredPayments = Payment::where('status', 'pending')
                                ->where('created_at', '<', now()->subMinutes(5))
                                ->get();

        $count = $expiredPayments->count();

        foreach ($expiredPayments as $payment) {
            $payment->update(['status' => 'expired']);
        }

        $this->info("Marked {$count} payments as expired.");
        
        return Command::SUCCESS;
    }
}
