<?php

namespace Dipantry\Rupiah\Tests\Exception;

use Dipantry\Rupiah\Enums\CurrencyCode;
use Dipantry\Rupiah\Exception\HttpResponseException;
use Dipantry\Rupiah\RupiahService;
use Dipantry\Rupiah\Service\KursService;
use Dipantry\Rupiah\Tests\TestCase;
use Exception;
use Illuminate\Support\Facades\Config;

class HttpResponseExceptionTest extends TestCase
{
    public function testConnectionTimedOut()
    {
        Config::set('rupiah.timeout', 1);

        try {
            $data = (new RupiahService())->of(10000)->buy(CurrencyCode::AUD);
            self::assertNotEmpty($data);
        } catch (Exception $e) {
            self::assertInstanceOf(HttpResponseException::class, $e);
            self::assertEquals('Connection Timed Out', $e->getMessage());
        }
    }

    public function testWeekend()
    {
        try {
            (new KursService())->getKurs(CurrencyCode::USD, "2023-02-18");
        } catch (Exception $e) {
            self::assertInstanceOf(HttpResponseException::class, $e);
            self::assertEquals('Weekend is not allowed', $e->getMessage());
        }
    }
}