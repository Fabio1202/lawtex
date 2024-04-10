<?php

namespace App\Console\Commands;

use Enlightn\Enlightn\Enlightn;
use Illuminate\Console\Command;

class ProductionEnlightnCommand extends Command
{
    protected $signature = 'prod:enlightn';

    protected $description = 'Command description';

    public function handle(): void
    {
        if (config('enlightn.run_on_production')) {
            // Get all Enlightn test classes
            $tests = Enlightn::getAnalyzerClasses();

            $shouldNotRun = config('enlightn.dont_run_on_production', []);

            // Filter out tests that should not run on production
            $tests = array_filter($tests, function ($test) use ($shouldNotRun) {
                return ! in_array($test, $shouldNotRun);
            });

            $this->info('Running Enlightn on production...');
            if (config('enlightn.credentials.username', '') !== '' && config('enlightn.credentials.api_token', '') !== '') {
                echo 'Running with report';
                $this->call('enlightn', [
                    '--report' => true,
                    'analyzer' => $tests,
                ]);
            } else {
                $this->call('enlightn', [
                    $tests,
                    'analyzer' => $tests,
                ]);
            }
        }
    }
}
