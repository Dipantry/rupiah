<?php

namespace Dipantry\Rupiah\Tests\Commands;

use Dipantry\Rupiah\Models\Bank;
use Dipantry\Rupiah\Tests\TestCase;

class BankRefreshCommandTest extends TestCase
{
    public function testCommand()
    {
        $this->artisan('rupiah:bank');

        $banks = Bank::all();
        self::assertNotEmpty($banks);
    }
}