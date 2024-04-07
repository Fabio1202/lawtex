<?php

namespace App\Providers;

use App\Parsers\Base\LawParser;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LawParser::class, function () {
            return new LawParser();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(UrlGenerator $url): void
    {
        if (config('app.force_https')) {
            $url->forceScheme('https');
        }
    }
}
