<?php

declare(strict_types=1);

namespace Drupal\currency\Model;

use Drupal\currency\Helper\Config;
use Exception;

class FreeCurrencyApi
{
    public const API_URL = 'https://api.freecurrencyapi.com/v1/';

    public function __construct(
        protected Curl $curl,
        protected Config $config
    ) {
    }

    /**
     * @throws Exception
     */
    public function getCurrencies(): array
    {
        $requestUrl = self::API_URL.'currencies';
        $result = $this->apiCall($requestUrl);

        if (isset($result['data'])) {
            return array_keys($result['data']);
        }

        return [];
    }

    /**
     * @throws Exception
     */
    public function getRate(string $currencyCode, string $baseCurrencyCode): float
    {
        $rates = $this->getRates([$currencyCode], $baseCurrencyCode);

        return array_pop($rates);
    }

    /**
     * @throws Exception
     */
    public function getRates(array $currencyCodes, string $baseCurrencyCode): array
    {
        $requestUrl = self::API_URL.'latest';

        $rates = $this->apiCall(
            $requestUrl,
            [
                'base_currency' => $baseCurrencyCode,
                'currencies' => implode(',', $currencyCodes),
            ]
        );

        return $rates['data'];
    }

    /**
     * @throws Exception
     */
    protected function apiCall(string $url, array $params = [], string $method = Curl::METHOD_GET): array
    {
        if ($this->config->getApiKey()) {
            return $this->curl->call($url, $params, $this->getHeaders(), $method);
        }

        return [];
    }

    protected function getHeaders(): array
    {
        return [
            'User-Agent: Freecurrencyapi/PHP/0.1',
            'Accept: application/json',
            'Content-Type: application/json',
            'apikey: '.$this->config->getApiKey()
        ];
    }
}
