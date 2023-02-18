<?php

namespace Dipantry\Rupiah\Tests;

use Dipantry\Rupiah\Facade;
use JetBrains\PhpStorm\ArrayShape;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    public function getPackageProviders($app): array
    {
        return [
            FakeServiceProvider::class,
        ];
    }

    #[ArrayShape(['Rupiah' => 'string'])]
    public function getPackageAliases($app): array
    {
        return [
            'Rupiah' => Facade::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
        ]);
        $app['config']->set('rupiah.table_prefix', 'rupiah_');
    }
}