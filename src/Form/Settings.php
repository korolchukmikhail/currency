<?php

declare(strict_types=1);

namespace Drupal\currency\Form;

use Drupal;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\currency\Api\ConfigInterface;
use Drupal\currency\Model\Currency;
use Exception;

class Settings extends ConfigFormBase
{
    public function getFormId(): string
    {
        return 'currency_settings';
    }

    protected function getEditableConfigNames(): array
    {
        return [
            ConfigInterface::CONFIG_KEY
        ];
    }

    /**
     * @throws Exception
     */
    public function buildForm(array $form, FormStateInterface $form_state): array
    {
        $config = $this->config(ConfigInterface::CONFIG_KEY);

        $form[ConfigInterface::API_KEY] = [
            '#type' => 'textfield',
            '#title' => $this->t('API Key'),
            '#default_value' => $config->get(ConfigInterface::API_KEY)
        ];

        $form['note'] = [
            '#type' => 'markup',
            '#markup' => $this->t('If the options below are empty, try saving the correct "API Key" first')
        ];

        $form[ConfigInterface::BASE_CURRENCY] = [
            '#type' => 'select',
            '#options' => $this->getCurrenciesOptions(),
            '#title' => $this->t('Base Currency'),
            '#default_value' => $config->get(ConfigInterface::BASE_CURRENCY)
        ];

        $form[ConfigInterface::AVAILABLE_CURRENCIES] = [
            '#type' => 'select',
            '#title' => $this->t('Available Currencies'),
            '#options' => $this->getCurrenciesOptions(),
            '#multiple' => true,
            '#default_value' => $config->get(ConfigInterface::AVAILABLE_CURRENCIES)
        ];

        $form[ConfigInterface::CRON_STATUS] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Cron Enabled'),
            '#default_value' => $config->get(ConfigInterface::CRON_STATUS)
        ];

        $form[ConfigInterface::CRON_INTERVAL] = [
            '#type' => 'textfield',
            '#title' => $this->t('Cron interval'),
            '#default_value' => $config->get(ConfigInterface::CRON_INTERVAL)
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state): void
    {
        $this->config(ConfigInterface::CONFIG_KEY)
            ->set(ConfigInterface::API_KEY, $form_state->getValue(ConfigInterface::API_KEY))
            ->set(ConfigInterface::BASE_CURRENCY, $form_state->getValue(ConfigInterface::BASE_CURRENCY))
            ->set(ConfigInterface::AVAILABLE_CURRENCIES, $form_state->getValue(ConfigInterface::AVAILABLE_CURRENCIES))
            ->set(ConfigInterface::CRON_STATUS, $form_state->getValue(ConfigInterface::CRON_STATUS))
            ->set(ConfigInterface::CRON_INTERVAL, $form_state->getValue(ConfigInterface::CRON_INTERVAL))
            ->save();

        parent::submitForm($form, $form_state);
    }

    /**
     * @throws Exception
     */
    public function getCurrenciesOptions(): array
    {
        /** @var Currency $currencyModel */
        $currencyModel = Drupal::service('currency.currency');

        if (!($currencies = $currencyModel->getAllCurrencies())) {
            $currencyModel->updateCurrenciesList();
            $currencies = $currencyModel->getAllCurrencies();
        }

        if ($currencies) {
            return array_combine(
                array_column($currencies, 'code'),
                array_column($currencies, 'code')
            );
        }

        return [];
    }
}
