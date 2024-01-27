<?php

namespace YG\Tcmb;

class Tcmb implements TcmbInterface
{
    private ?string
        $apiUrl,
        $apiKey;

    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
        $this->apiUrl = null;
        $this->apiKey = null;
    }

    public function setApiInfo(string $url, string $key): void
    {
        $this->apiUrl = $url;
        $this->apiKey = $key;
    }

    public function getCurrencyRates(array $currencies): CurrencyRateResultSetInterface
    {
        $series = array_map(function ($item) {
            return 'TP.DK.' . $item . '.A-TP.DK.' . $item . '.S';
        }, $currencies);

        $params = [
            'series' => join('-', $series),
            'startDate' => Date('d-m-Y'),
            'endDate' => Date('d-m-Y'),
            'type' => 'json',
            'key' => $this->apiKey
        ];

        $url = $this->apiUrl . join('&', array_map(function ($value, $key) {
                return $key . '=' . $value;
            }, $params, array_keys($params)));

        return CurrencyRateResultSet::createByApi($this->request->get($url));
    }

    public function getCurrencyRatesByXML(array $currencies): CurrencyRateResultSetInterface
    {
        return CurrencyRateResultSet::createByXml($this->request->getByXML($currencies));
    }
}