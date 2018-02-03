<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 12:26 AM
 */

namespace AppBundle\Domain\Model\Trading;


class AmountFactory
{
    /**
     * @param float $value
     * @param Currency $currency
     * @return Amount
     */
    public static function makeAmount($value, $currency)
    {
        return (new Amount())->setValue($value)->setCurrency($currency);
    }
}