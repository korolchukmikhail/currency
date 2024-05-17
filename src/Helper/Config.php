<?php

declare(strict_types=1);

namespace Drupal\currency\Helper;

use Drupal;
use Drupal\currency\Api\ConfigInterface;

class Config implements ConfigInterface
{
    public function getApiKey(): string
    {
        return (string)$this->getDrupalConfig()->get(self::API_KEY);
    }

    public function getCronStatus(): bool
    {
        return (bool)$this->getDrupalConfig()->get(self::CRON_STATUS);
    }

    public function getCronInterval(): int
    {
        return (int)$this->getDrupalConfig()->get(self::CRON_INTERVAL);
    }

    public function getBaseCurrency(): string
    {
        return (string)$this->getDrupalConfig()->get(self::BASE_CURRENCY);
    }

    public function getAvailableCurrencies(): array
    {
        return (array)$this->getDrupalConfig()->get(self::AVAILABLE_CURRENCIES);
    }

    protected function getDrupalConfig(): Drupal\Core\Config\ImmutableConfig
    {
        return Drupal::config(self::CONFIG_KEY);
    }
}
