<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 12:21 PM
 */

namespace AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\Amount;
use AppBundle\Domain\Model\Trading\Currency;
use AppBundle\Domain\Model\Trading\Interest;
use AppBundle\Domain\Model\Util\InvalidArgumentException;

class AmountService
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

    /**
     * @facade
     * @param float $value
     * @param string $currencySymbol
     * @return Amount
     * @throws InvalidArgumentException
     */
    public static function buildAmount($value, $currencySymbol)
    {
        /**
         * @todo - get from Repository
         */
        static $currencyPrecision = [
            'LEI' => 2,
            'USD' => 2,
            'EUR' => 2,
        ];
        if (!array_key_exists($currencySymbol, $currencyPrecision)) {
            throw new InvalidArgumentException("Invalid currency: {$currencySymbol}", InvalidArgumentException::ERR_CURRENCY_INVALID);
        }
        return (new Amount())->setValue($value)->setCurrency(CurrencyService::makeCurrency($currencySymbol, $currencyPrecision[$currencySymbol]));
    }

    /**
     * @param Amount $amount
     * @param Interest $interest
     * @param \DateInterval $evaluationInterval
     * @return Amount
     */
    public function getAmountInterestForInterval(Amount $amount, Interest $interest, \DateInterval $evaluationInterval)
    {
        $evaluatedInterest = InterestService::makeInterest($interest->getPercent(), $interest->getInterval());
        $evaluatedInterest->setInterval($evaluationInterval);

        return static::makeAmount(
            round($evaluatedInterest->getPercent() * $amount->getValue() / 100, $amount->getCurrency()->getPrecision()),
            $amount->getCurrency()
        );
    }

}