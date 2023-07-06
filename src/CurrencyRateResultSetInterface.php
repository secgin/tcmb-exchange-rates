<?php

namespace YG\Tcmb;

interface CurrencyRateResultSetInterface
{
    public function getTime(): string;

    /**
     * @return CurrencyRate[]
     */
    public function getRates(): array;

    public function getRate(string $currencyCode): ?CurrencyRate;
}