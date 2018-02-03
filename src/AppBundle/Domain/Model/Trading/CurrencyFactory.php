<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 12:34 AM
 */

namespace AppBundle\Domain\Model\Trading;


class CurrencyFactory
{
    public static function makeCurrency($symbol = null, $precision = null)
    {
        return (new Currency())->setSymbol($symbol)->setPrecision($precision);
    }
}