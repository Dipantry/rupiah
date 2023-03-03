<?php

/** @noinspection SpellCheckingInspection */

namespace Dipantry\Rupiah\Service;

use Carbon\Carbon;
use Dipantry\Rupiah\Constants\URLs;
use Dipantry\Rupiah\Enums\CurrencyCode;
use Dipantry\Rupiah\Exception\HttpResponseException;
use Dipantry\Rupiah\Exception\InvalidCurrencyException;
use Dipantry\Rupiah\Traits\HttpService;
use DOMDocument;
use DOMXPath;

class KursService
{
    use HttpService;

    private DOMDocument $doc;
    private DOMXPath $xpath;

    /**
     * @throws HttpResponseException|InvalidCurrencyException
     */
    public function getKurs(string $code, string $dateString = null): array
    {
        $dateString = $dateString ?? Carbon::now()->toDateString();

        if (!CurrencyCode::isValidValue($code)) {
            throw new InvalidCurrencyException('Invalid currency code');
        }

        if (Carbon::parse($dateString)->isWeekend()) {
            throw new HttpResponseException('Weekend is not allowed');
        }

        $params = [
            'mts'       => $code,
            'startdate' => $dateString,
            'enddate'   => $dateString,
        ];
        $xmlString = $this->get(URLs::$kursUrl, $params)->body();

        return $this->processResult($xmlString);
    }

    private function processResult(string $xml): array
    {
        $multiplier = $this->getDataFromXml($xml, 'nil_subkursasing');
        $buy = $this->getDataFromXml($xml, 'beli_subkursasing');
        $sell = $this->getDataFromXml($xml, 'jual_subkursasing');

        if ($multiplier === '0' || $buy === '0' || $sell === '0') {
            return [
                'buy'  => 0,
                'sell' => 0,
            ];
        } else {
            return [
                'buy'  => $buy / $multiplier,
                'sell' => $sell / $multiplier,
            ];
        }
    }

    private function getDataFromXml(string $xml, string $tag): string
    {
        return preg_match('/<'.$tag.'>(.*?)<\/'.$tag.'>/', $xml, $matches) ? $matches[1] : '0';
    }
}
