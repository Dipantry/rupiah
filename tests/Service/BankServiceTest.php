<?php

namespace Dipantry\Rupiah\Tests\Service;

use Dipantry\Rupiah\Service\BankService;
use Dipantry\Rupiah\Tests\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;

class BankServiceTest extends TestCase
{
    use InteractsWithDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testGetBankList()
    {
        $result = (new BankService())->getBankList();
        $this->assertNotEmpty($result);
    }
}
