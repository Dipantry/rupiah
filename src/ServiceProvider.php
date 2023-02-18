<?php

namespace Dipantry\Rupiah;

use Dipantry\Rupiah\Commands\BankRefreshCommand;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->bind('rupiah', function () {
            return new RupiahService();
        });

        $this->commands([
            BankRefreshCommand::class,
        ]);
    }

    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/rupiah.php',
            'rupiah',
        );

        $databasePath = __DIR__.'/../database/migrations';
        if ($this->isLumen()) {
            $this->loadMigrationsFrom($databasePath);
        } else {
            $this->publishes(
                [$databasePath => database_path('migrations')],
                'migrations'
            );
        }

        if (class_exists(Application::class)) {
            $this->publishes([
                __DIR__.'/../config/rupiah.php' => config_path('rupiah.php'),
            ], 'config');
        }
    }

    protected function isLaravel(): bool
    {
        return app() instanceof Application;
    }

    protected function isLumen(): bool
    {
        return !$this->isLaravel();
    }
}