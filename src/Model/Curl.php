<?php

declare(strict_types=1);

namespace Drupal\currency\Model;

use Exception;

class Curl
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';

    /**
     * @throws Exception
     */
    public function call(string $url, array $params, array $headers, string $method = self::METHOD_GET): array
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

        if ($method === self::METHOD_POST) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, count($params));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        } else {
            curl_setopt($curl, CURLOPT_URL, $url.'?'.http_build_query($params));
        }

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new Exception(curl_error($curl));
        }

        $response = json_decode($response, true);
        curl_close($curl);

        return $response;
    }
}
