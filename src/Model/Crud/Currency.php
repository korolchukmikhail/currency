<?php

declare(strict_types=1);

namespace Drupal\currency\Model\Crud;

use Drupal\currency\Api\CurrencyTableInterface;
use Exception;

class Currency extends Base
{
    protected string $table = CurrencyTableInterface::TABLE_NAME;
    protected string $idField = CurrencyTableInterface::ID_KEY;

    /**
     * $data = [
     *      ['code'=>'USD'],
     *      ['code'=>'EUR']
     *      ...
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
