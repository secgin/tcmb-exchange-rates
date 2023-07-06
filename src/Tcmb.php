<?php

namespace YG\Tcmb;

class Tcmb implements TcmbInterface
{
    private string $apiUrl;
    private string $apiKey;
    private Request $request;

    public function __construct(string $apiUrl, string $apiKey)
    {
        $this->request = new Request();
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    public function getCurrencyRates(array $currencies): CurrencyRateResultSetInterface
    {
        $series = array_map(function($item) {
            return 'TP.DK.' . $item . '.A-TP.DK.' . $item . '.S';
        }, $currencies);

        $params = [
            'series' => join('-', $series),
            'startDate' => Date('d-m-Y'),
            'endDate' => Date('d-m-Y'),
            'type' => 'json',
            'key' => $this->apiKey
        ];

        $url = $this->apiUrl . join('&', array_map(function($value, $key) {
                return $key . '=' . $value;
            }, $params, array_keys($params)));

        $result = $this->request->get($url);
        return new CurrencyRateResultSet($result);
    }
}