<?php

namespace YG\Tcmb;

interface TcmbInterface
{
    /**
     * @param array $currencies ['USD', 'EUR', ...]
     *
     * @return CurrencyRateResultSetInterface
     */
    public function getCurrencyRates(array $currencies): CurrencyRateResultSetInterface;

    /**
     * @param array $currencies ['USD', 'EUR', ...]
     *
     * @return CurrencyRateResultSetInterface
     */
    public function getCurrencyRatesByXML(array $currencies): CurrencyRateResultSetInterface;
}