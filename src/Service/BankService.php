<?php

namespace Dipantry\Rupiah\Service;

use Dipantry\Rupiah\Constants\URLs;
use DOMDocument;
use DOMNodeList;
use DOMXPath;

class BankService
{
    private HttpService $service;

    private DOMDocument $doc;
    private DOMXPath $xpath;

    public function __construct()
    {
        $this->service = new HttpService();
    }

    public function getBankList(): array
    {
        libxml_use_internal_errors(true);
        $htmlString = $this->service->get(URLs::$bankUrl, []);

        $this->doc = new DOMDocument();
        $this->doc->loadHTML($htmlString);
        $this->xpath = new DOMXPath($this->doc);

        return $this->processResult($this->xpath->query("//*[contains(@class, 'chakra-table')]"));
    }

    private function processResult(DOMNodeList $tables): array
    {
        $results = [];
        foreach ($tables as $index => $table) {
            $tbody = $this->xpath->query("tbody", $table)->item(0);
            $tr = $this->xpath->query("tr", $tbody);

            foreach ($tr as $row) {
                $cols = $this->xpath->query("td", $row);

                if ($cols[1]->getElementsByTagName('a')->length > 0) {
                    $result['name'] = $cols[1]->getElementsByTagName('a')[0]->nodeValue;
                } else {
                    $result['name'] = $cols[1]->nodeValue;
                }
                $result['code'] = $cols[2]->nodeValue;

                $results[] = $result;
            }
        }

        return $results;
    }
}