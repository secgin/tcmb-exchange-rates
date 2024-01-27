<?php

namespace YG\Tcmb;

final class CurrencyRateResultSet implements CurrencyRateResultSetInterface
{
    private array $data = [];

    private string $time;

    public function __construct(?object $result)
    {
        if ($result != null)
            $this->prepareData($result);
    }

    public function getTime(): string
    {
        return $this->time ?? '';
    }

    public function getRates(): array
    {
        return $this->data;
    }

    public function getRate(string $currencyCode): ?CurrencyRate
    {
        if (isset($this->data[$currencyCode]))
            return $this->data[$currencyCode];

        return null;
    }

    private function prepareData(object $result): void
    {
        $item = $result->items[0];
        $this->time = gmdate('Y.m-d H:i:s', $item->UNIXTIME->{'$numberLong'} ?? 0);

        $rates = [];
        foreach ($item as $name => $value)
        {
            if (substr($name, 0, 6) == 'TP_DK_')
            {
                $currencyCode = substr($name, 6, 3);
                $type = substr($name, 10, 1);
                $rates[$currencyCode][$type] = $value;
            }
        }

        $this->data = [];
        foreach ($rates as $code => $item)
        {
            $currencyRate = new CurrencyRate();
            $currencyRate->code = $code;
            $currencyRate->buying = $item['A'] ?? 0;
            $currencyRate->selling = $item['S'] ?? 0;
            $currencyRate->time = $this->time;

            $this->data[$code] = $currencyRate;
        }
    }
}