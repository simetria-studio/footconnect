<?php

namespace App\Console\Commands;

use App\Services\ReferralService;
use Illuminate\Console\Command;

class ProcessReferralPayouts extends Command
{
    protected $signature = 'referrals:process-payouts';

    protected $description = 'Libera comissões pendentes e processa saques automáticos via PIX';

    public function handle(ReferralService $referralService): int
    {
        $released = $referralService->processPendingCommissions();
        $paid = $referralService->processAutomaticPayouts();

        $this->info("Comissões liberadas: {$released}. Saques automáticos: {$paid}.");

        return self::SUCCESS;
    }
}
