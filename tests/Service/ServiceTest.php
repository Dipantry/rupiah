<?php

/** @noinspection PhpUndefinedClassInspection */

namespace Dipantry\Rupiah\Tests\Service;

use Dipantry\Rupiah\Enums\CurrencyCode;
use Dipantry\Rupiah\Tests\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Rupiah;

class ServiceTest extends TestCase
{
    use InteractsWithDatabase;

    public function testValue()
    {
        $rupiah = Rupiah::of(10000);
        $this->assertEquals(10000, $rupiah->getValue());
    }

    public function testBuyCurrency()
    {
        $rupiah = Rupiah::of(10000);
        $usd = $rupiah->buy(CurrencyCode::USD);

        $this->assertLessThan(1, $usd);

        $gbp = $rupiah->buy(CurrencyCode::GBP);
        $this->assertLessThan(1, $gbp);
    }

    public function testSellCurrency()
    {
        $rupiah = Rupiah::of(1);
        $usdToIDR = $rupiah->sell(CurrencyCode::USD);

        $this->assertGreaterThan(10000, $usdToIDR);

        $gbpToIDR = $rupiah->sell(CurrencyCode::GBP);
        $this->assertGreaterThan(10000, $gbpToIDR);
    }

    public function testExchangeRate()
    {
        $usdToIDR = Rupiah::exchangeRate(CurrencyCode::USD);

        $this->assertNotEmpty($usdToIDR['buy']);
        $this->assertNotEmpty($usdToIDR['sell']);
    }

    public function testWords()
    {
        $rupiah = Rupiah::of(10000);
        $words = $rupiah->toWords();

        $this->assertEquals('Sepuluh Ribu Rupiah', $words);
    }
}