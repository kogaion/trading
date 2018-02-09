<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 12:34 PM
 */

namespace AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\Amount;
use AppBundle\Domain\Model\Trading\Interest;

class InterestService
{
    /**
     * @param int $percent
     * @param \DateInterval $interval
     * @return Interest
     */
    public static function makeInterest($percent, \DateInterval $interval)
    {
        return (new Interest())->setPercent($percent)->setInterval($interval);
    }

    /**
     * @param Amount $amount
     * @param Interest $interest
     * @param \DateInterval $evaluationInterval
     * @return Amount
     */
    public function getInterestForInterval(Amount $amount, Interest $interest, \DateInterval $evaluationInterval)
    {
        $evaluatedInterest = InterestService::makeInterest($interest->getPercent(), $interest->getInterval());
        $evaluatedInterest->setInterval($evaluationInterval);

        return AmountService::makeAmount(
            round($evaluatedInterest->getPercent() * $amount->getValue() / 100, $amount->getCurrency()->getPrecision()),
            $amount->getCurrency()
        );
    }
}