<?php

declare(strict_types=1);

namespace Drupal\currency\Api;

interface CurrencyInterface
{
    public function getCurrencyRate(string $currency, string $baseCurrency = ''): float;

    public function getAvailableCurrencies(): array;

    public function getAvailableRates(): array;

    public function getAllCurrencies(): array;

    public function updateCurrenciesList(): void;

    public function convert(float $amount, string $fromCurrency, string $toCurrency = ''): float;
}
