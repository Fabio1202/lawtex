<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProductionEnlightnCommand extends Command
{
    protected $signature = 'prod:enlightn';

    protected $description = 'Command description';

    public function handle(): void
    {
        if(config('enlightn.run_on_production')) {
            $this->info('Running Enlightn on production...');
            if(config('enlightn.credentials.username') && config('enlightn.credentials.api_token')) {
                $this->call('enlightn', [
                    '--report'
                ]);
            } else {
                $this->call('enlightn');
            }
        }
    }
}
