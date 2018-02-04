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