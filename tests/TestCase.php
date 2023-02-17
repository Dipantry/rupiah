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
    }

    public function getPackageProviders($app)
    {
        return [
            FakeServiceProvider::class,
        ];
    }

    #[ArrayShape(['Rupiah' => 'string'])]
    public function getPackageAliases($app)
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
            'prefix'   => 'rupiah_',
        ]);
    }
}