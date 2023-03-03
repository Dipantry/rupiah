<?php

namespace Dipantry\Rupiah\Tests\Exception;

use Dipantry\Rupiah\Exception\InvalidCurrencyException;
use Dipantry\Rupiah\RupiahService;
use Dipantry\Rupiah\Tests\TestCase;
use Exception;

class InvalidCurrencyExceptionTest extends TestCase
{
    public function testWrongCurrencyCode()
    {
        try {
            (new RupiahService())->buy('ASC');
        } catch (Exception $e) {
            self::assertInstanceOf(InvalidCurrencyException::class, $e);
            self::assertEquals('Invalid currency code', $e->getMessage());
        }
    }
}
