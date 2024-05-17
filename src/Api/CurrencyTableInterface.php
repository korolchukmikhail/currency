<?php

declare(strict_types=1);

namespace Drupal\currency\Api;

interface CurrencyTableInterface
{
    public const TABLE_NAME = 'currency';
    public const ID_KEY = 'code';
}
