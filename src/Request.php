<?php

namespace YG\Tcmb;

final class Request
{
    public function get(string $url, array $data = [], array $header = []): ?object
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array_merge(
                [
                    'Content-Type: application/json'
                ],
                $header),
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_POSTFIELDS => json_encode($data)
        ]);
        set_time_limit(0);
        $result = curl_exec($ch);

        if ($result === false)
            return null;

        return json_decode($result);
    }

    public function getByXML(array $currencies): ?array
    {
        $response = simplexml_load_file("https://www.tcmb.gov.tr/kurlar/today.xml");
        if ($response === false)
            return null;

        $result = [];

        $date = (string)$response->attributes()->Tarih;
        $time = $date != ''
            ? date('Y-m-d', strtotime($date))
            : '';

        $result['date'] = $time;


        $rates = [];
        foreach ($response->Currency as $item)
        {
            $currencyCode = (string)$item->attributes()->CurrencyCode;

            if (!in_array($currencyCode, $currencies))
                continue;

            $buying = (float)$item->ForexBuying;
            $selling = (float)$item->ForexSelling;

            $rates[] = [
                'code' => $currencyCode,
                'buying' => $buying,
                'selling' => $selling,
                'time' => $time
            ];
        }
        $result['rates'] = $rates;

        return $result;
    }
}