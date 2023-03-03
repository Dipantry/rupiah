<?php

namespace Dipantry\Rupiah\Tests\Service;

use Dipantry\Rupiah\Enums\CurrencyCode;
use Dipantry\Rupiah\Service\KursService;
use Dipantry\Rupiah\Tests\TestCase;
use Exception;

class KursServiceTest extends TestCase
{
    public function testGetKursUS()
    {
        try {
            $result = (new KursService())->getKurs(CurrencyCode::USD, '2023-02-17');
        } catch (Exception) {
            $this->fail('Exception is not expected');
        }

        $this->assertNotEmpty($result);

        $this->assertEquals(14676.0, $result['buy']);
        $this->assertEquals(15676.0, $result['sell']);
    }

    public function testGetKursWeekend()
    {
        try {
            (new KursService())->getKurs(CurrencyCode::USD, '2023-02-18');
        } catch (Exception $e) {
            $this->assertEquals('Weekend is not allowed', $e->getMessage());
        }
    }
}
