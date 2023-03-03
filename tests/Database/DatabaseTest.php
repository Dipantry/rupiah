<?php

namespace Dipantry\Rupiah\Tests\Database;

use Dipantry\Rupiah\Models\Bank;
use Dipantry\Rupiah\Tests\TestCase;

class DatabaseTest extends TestCase
{
    public function testBankDatabaseSeed()
    {
        $this->artisan('rupiah:bank');

        $banks = Bank::all();
        $this->assertNotEmpty($banks);

        $this->assertEquals(156, $banks->count());

        $bank = Bank::find(1);
        $this->assertEquals('Bank Bca', $bank->name);
        $this->assertEquals('Bank Central Asia', $bank->alt_name);
        $this->assertEquals('014', $bank->code);
    }
}
