<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\SchoolDataProvider;
use App\Services\Wonde\WondeSchoolDataProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;
use Wonde\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Client::class, function (Application $app) {
            return new Client(config('services.wonde.key'));
        });

        $this->app->singleton(SchoolDataProvider::class, function (Application $app) {
            return new WondeSchoolDataProvider(
                $app->make(Client::class),
                $app->make(LoggerInterface::class),
                config('services.wonde.school')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
