<?php

declare(strict_types=1);

namespace Drupal\currency\Model\Crud;

use Drupal\currency\Api\CurrencyRateTableInterface;
use Exception;

class CurrencyRate extends Base
{
    protected string $table = CurrencyRateTableInterface::TABLE_NAME;
    protected string $idField = CurrencyRateTableInterface::ID_KEY;

    /**
     * $data = [
     *      ['currency'=>'USD', 'base_currency'=>'EUR', 'rate'=>1.2],
     *      ['currency'=>'USD', 'base_currency'=>'USD', 'rate'=>1],
     *      [...]
     * ]
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function save(array $data): void
    {
        parent::save($data);
    }
}
