<?php

namespace Dipantry\Rupiah\Tests\Model;

use Dipantry\Rupiah\Models\Bank;
use Dipantry\Rupiah\Tests\TestCase;

class BankTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('rupiah:bank');
    }

    public function testManyBanks()
    {
        $banks = Bank::all();
        self::assertEquals(156, $banks->count());
    }

    public function testFirstBank()
    {
        $bank = Bank::first();
        self::assertEquals('Bank Bca', $bank->name);
        self::assertEquals('Bank Central Asia', $bank->alt_name);
        self::assertEquals('014', $bank->code);
    }
}
