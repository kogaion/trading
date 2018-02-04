<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 8:17 PM
 */

namespace AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\Currency;


class CurrencyService
{
    /**
     * @param string $symbol
     * @param int|null $precision
     * @return Currency
     */
    public static function makeCurrency($symbol, $precision = 2)
    {
        return (new Currency())->setSymbol($symbol)->setPrecision($precision);
    }
}