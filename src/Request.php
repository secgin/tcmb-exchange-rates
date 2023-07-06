<?php

namespace YG\Tcmb;

final class Request
{
    public function get(string $url, array $data = [], array $header = []): object
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
            CURLOPT_POSTFIELDS => json_encode($data)
        ]);
        $result = curl_exec($ch);

        return json_decode($result);
    }
}