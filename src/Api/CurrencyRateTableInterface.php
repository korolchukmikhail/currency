<?php

declare(strict_types=1);

namespace Drupal\currency\Api;

interface CurrencyRateTableInterface
{
    public const TABLE_NAME = 'currency_rate';
    public const ID_KEY = 'id';

    public const CURRENCY_KEY = 'currency';
    public const BASE_CURRENCY_KEY = 'base_currency';
    public const RATE_KEY = 'rate';
    public const CREATED_AT_KEY = 'created_at';
    public const UPDATED_AT_KEY = 'updated_at';
}
