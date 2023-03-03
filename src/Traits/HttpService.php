<?php

namespace Dipantry\Rupiah\Traits;

use Dipantry\Rupiah\Exception\HttpResponseException;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

trait HttpService
{
    private int $timeout;
    private int $retry;

    public function __construct()
    {
        $this->timeout = config('rupiah.timeout');
        $this->retry = config('rupiah.max_retry');
    }

    /* @throws HttpResponseException */
    public function get(string $url, array $params): Response
    {
        return $this->getHttp($url, $params, $this->retry);
    }

    /* @throws HttpResponseException */
    public function post(string $url, array $params): Response
    {
        return $this->postHttp($url, $params, $this->retry);
    }

    /* @throws HttpResponseException */
    private function getHttp(string $url, array $params, int $retry): Response
    {
        try {
            return Http::timeout($this->timeout)
                ->get($url, $params);
        } catch (Exception) {
            if ($retry > 0) {
                return $this->getHttp($url, $params, $retry - 1);
            }

            throw new HttpResponseException('Connection Timed Out');
        }
    }

    /* @throws HttpResponseException */
    private function postHttp(string $url, array $params, int $retry): Response
    {
        try {
            return Http::timeout($this->timeout)
                ->post($url, $params);
        } catch (Exception) {
            if ($retry > 0) {
                return $this->postHttp($url, $params, $retry - 1);
            }

            throw new HttpResponseException('Connection Timed Out');
        }
    }
}
