<?php

namespace Dipantry\Rupiah;

use Carbon\Carbon;
use Dipantry\Rupiah\Exception\HttpResponseException;
use Dipantry\Rupiah\Exception\InvalidCurrencyException;
use Dipantry\Rupiah\Service\KursService;
use Dipantry\Rupiah\Service\NumberService;

class RupiahService
{
    private KursService $kursService;
    private NumberService $numberService;

    private float $value;

    public function __construct()
    {
        $this->kursService = new KursService();
        $this->numberService = new NumberService();
    }

    public function of(float $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @throws HttpResponseException
     * @throws InvalidCurrencyException
     */
    public function buy(string $code): float
    {
        if (Carbon::now()->isWeekend()) {
            throw new HttpResponseException('Weekend is not allowed');
        }

        $rate = $this->kursService->getKurs($code)['sell'];
        return $this->value / $rate;
    }

    /**
     * @throws HttpResponseException
     * @throws InvalidCurrencyException
     */
    public function sell(string $code): float
    {
        if (Carbon::now()->isWeekend()) {
            throw new HttpResponseException('Weekend is not allowed');
        }

        $rate = $this->kursService->getKurs($code)['buy'];
        return $this->value * $rate;
    }

    /**
     * @throws InvalidCurrencyException
     * @throws HttpResponseException
     */
    public function exchangeRate(string $code, string $date = null): array
    {
        return $this->kursService->getKurs($code, $date);
    }

    public function toWords(): string
    {
        return $this->numberService->formatText($this->value);
    }
}