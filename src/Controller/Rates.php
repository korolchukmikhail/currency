<?php

declare(strict_types=1);

namespace Drupal\currency\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Drupal\currency\Api\CurrencyRateTableInterface;
use Drupal\currency\Helper\Config;
use Drupal\currency\Model\Currency;

class Rates extends ControllerBase
{
    /**
     * @throws \Exception
     */
    public function tableRates(): array
    {
        if (!$this->currentUser()->hasPermission('administer site')) {
            $this->messenger()->addError($this->t('You don\'t have permission to use this module.'));
        }

        /** @var Config $config */
        $config = Drupal::service('currency.config');

        $header = $this->t('Table rates');
        $description = $this->t('Base Currency is: ').'<b>'.$config->getBaseCurrency().'</b>';
        $tableHeaders = ['ID', 'Currency', 'Rate', 'Updated At'];
        $emptyMessage = $this->t('There are no currency rates.');

        /** @var Currency $currencyModel */
        $currencyModel = Drupal::service('currency.currency');
        $availableRates = $currencyModel->getAvailableRates();

        foreach ($availableRates as &$availableRate) {
            unset($availableRate[CurrencyRateTableInterface::BASE_CURRENCY_KEY]);
            unset($availableRate[CurrencyRateTableInterface::CREATED_AT_KEY]);
        }

        return [
            '#theme' => 'currency_rate',
            '#header' => $header,
            '#description' => $description,
            '#table_headers' => $tableHeaders,
            '#table_rows' => $availableRates,
            '#empty' => $emptyMessage
        ];
    }
}
