<?php

namespace YG\Tcmb;

final class CurrencyRateResultSet implements CurrencyRateResultSetInterface
{
    private array $data = [];

    private string $time;

    private function __construct()
    {
    }

    public static function createByApi(?object $result): CurrencyRateResultSet
    {
        $resultSet = new CurrencyRateResultSet();

        if ($result != null)
            $resultSet->prepareDataByApiResult($result);

        return $resultSet;
    }

    public static function createByXml(?array $data): CurrencyRateResultSet
    {
        $resultSet = new CurrencyRateResultSet();

        if ($data != null)
            $resultSet->prepareDataByXml($data);;

        return $resultSet;
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

    private function prepareDataByApiResult(object $result): void
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

    private function prepareDataByXml(array $data): void
    {
        $this->time = $data['date'];

        foreach ($data['rates'] as $currency)
        {
            $currencyRate = new CurrencyRate();
            $currencyRate->code = $currency['code'];
            $currencyRate->buying = $currency['buying'];
            $currencyRate->selling = $currency['selling'];
            $currencyRate->time = $currency['time'];

            $this->data[$currency['code']] = $currencyRate;
        }
    }
}