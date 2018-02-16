<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 12:34 PM
 */

namespace AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\Interest;
use AppBundle\Domain\Model\Util\DateTimeInterval;

class InterestService
{
    /**
     * @param double $percent
     * @param \DateInterval $interval
     * @param string $type
     * @return Interest
     */
    public function makeInterest($percent, \DateInterval $interval, $type = Interest::TYPE_FIXED)
    {
        return (new Interest())->setPercent($percent)->setInterval($interval)->setType($type);
    }
    
    /**
     * @param double $amount
     * @param Interest $interest
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     * @return double
     * @todo - different calculus for variable ?
     */
    public function getInterestForInterval($amount, Interest $interest, \DateTime $fromDate, \DateTime $toDate)
    {
        $interestRatio = $interest->getPercent();
        
        $interestInterval = DateTimeInterval::recalculate($fromDate, $interest->getInterval());
        
        $startDate = clone $fromDate;
        $currentInterval = $startDate->diff($toDate);
        
        return $amount * $interestRatio / 100 * $currentInterval->format('%a') / $interestInterval->format('%a');
    }
}