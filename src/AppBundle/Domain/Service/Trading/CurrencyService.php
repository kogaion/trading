<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 8:17 PM
 */

namespace AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\Currency;
use AppBundle\Domain\Model\Util\InvalidArgumentException;


class CurrencyService
{
    const DEFAULT_CURRENCY = 'LEI';

    /**
     * @param string $currencySymbol
     * @param int $currencyPrecision
     * @return Currency
     */
    public function makeCurrency($currencySymbol, $currencyPrecision)
    {
        return (new Currency())->setSymbol($currencySymbol)->setPrecision($currencyPrecision);
    }

    /**
     * @param string $currencySymbol
     * @return Currency
     */
    public function buildCurrency($currencySymbol)
    {
        $currencyPrecision = $this->searchCurrency($currencySymbol);
        return $this->makeCurrency($currencySymbol, $currencyPrecision);
    }

    /**
     * @param $currencySymbol
     * @return mixed
     * @throws InvalidArgumentException
     * @todo search in repository
     */
    protected function searchCurrency($currencySymbol)
    {
        $currencies = $this->loadCurrencies();
        if (!array_key_exists($currencySymbol, $currencies)) {
            throw new InvalidArgumentException("Invalid currency: {$currencySymbol}", InvalidArgumentException::ERR_CURRENCY_INVALID);
        }

        return $currencies[$currencySymbol];
    }

    /**
     * @return array
     * @todo Load from repository
     */
    protected function loadCurrencies()
    {
        return [
            'LEI' => 2,
            'USD' => 2,
            'EUR' => 2,
        ];
    }

}