<?php

declare(strict_types=1);

namespace Drupal\currency\Model;

use Drupal\currency\Helper\Config;
use Exception;

class Cron
{
    public function __construct(
        protected Config $config,
        protected crud\CurrencyRate $currencyRate,
        protected FreeCurrencyApi $freeCurrencyApi
    ) {
    }

    /**
     * @throws Exception
     */
    public function run(): void
    {
        $availableCurrencies = $this->config->getAvailableCurrencies();
        $baseCurrency = $this->config->getBaseCurrency();

        $rates = $this->freeCurrencyApi->getRates($availableCurrencies, $baseCurrency);
        $data = [];

        foreach ($rates as $currency => $rate) {
            $data[] = [
                'currency' => $currency,
                'base_currency' => $baseCurrency,
                'rate' => $rate
            ];
        }

        $this->currencyRate->save($data);
    }
}
