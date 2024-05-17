<?php

declare(strict_types=1);

namespace Drupal\currency\Model;

use Drupal\currency\Api\CurrencyInterface;
use Drupal\currency\Api\CurrencyRateTableInterface;
use Drupal\currency\Api\CurrencyTableInterface;
use Drupal\currency\Helper\Config;
use Exception;

class Currency implements CurrencyInterface
{
    public function __construct(
        protected Config $config,
        protected crud\Currency $currency,
        protected crud\CurrencyRate $currencyRate,
        protected FreeCurrencyApi $freeCurrencyApi
    ) {
    }

    /**
     * @throws Exception
     */
    public function getCurrencyRate(string $currency, string $baseCurrency = ''): float
    {
        if (!$baseCurrency) {
            $baseCurrency = $this->config->getBaseCurrency();
        }

        $availableCurrencies = $this->config->getAvailableCurrencies();

        if (!in_array($currency, $availableCurrencies)) {
            throw new Exception('Currency not available');
        }

        $rate = $this->currencyRate->getList([
            ['field' => CurrencyRateTableInterface::CURRENCY_KEY, 'value' => $currency],
            ['field' => CurrencyRateTableInterface::BASE_CURRENCY_KEY, 'value' => $baseCurrency, 'operator' => '=']
        ]);

        if (!$rate) {
            throw new Exception('Currency rate not found');
        }

        $rate = array_pop($rate);

        return (float)$rate['rate'];
    }

    public function getAvailableCurrencies(): array
    {
        return $this->config->getAvailableCurrencies();
    }

    /**
     * @throws Exception
     */
    public function getAvailableRates(): array
    {
        $baseCurrency = $this->config->getBaseCurrency();
        $availableCurrencies = $this->config->getAvailableCurrencies();

        if ($baseCurrency && $availableCurrencies) {
            return $this->currencyRate->getList([
                [
                    'field' => CurrencyRateTableInterface::CURRENCY_KEY,
                    'value' => $availableCurrencies,
                    'operator' => 'IN'
                ],
                ['field' => CurrencyRateTableInterface::BASE_CURRENCY_KEY, 'value' => $baseCurrency, 'operator' => '=']
            ]);
        }

        return [];
    }

    /**
     * @throws Exception
     */
    public function getAllCurrencies(): array
    {
        return $this->currency->getAll();
    }

    /**
     * @throws Exception
     */
    public function updateCurrenciesList(): void
    {
        $currencies = $this->freeCurrencyApi->getCurrencies();
        if ($currencies) {
            $data = [];

            foreach ($currencies as $currency) {
                $data[] = [CurrencyTableInterface::ID_KEY => $currency];
            }

            $this->currency->save($data);
        }
    }

    /**
     * @throws Exception
     */
    public function convert(float $amount, string $fromCurrency, string $toCurrency = ''): float
    {
        $rate = $this->getCurrencyRate($fromCurrency, $toCurrency);

        return $amount * $rate;
    }
}
