<?php

declare(strict_types=1);

namespace Drupal\currency\Api;

interface ConfigInterface
{
    public const CONFIG_KEY = 'currency.settings';

    public const API_KEY = 'api_key';
    public const CRON_STATUS = 'cron_enabled';
    public const CRON_INTERVAL = 'cron_interval';
    public const BASE_CURRENCY = 'config';
    public const AVAILABLE_CURRENCIES = 'available_currencies';
}
