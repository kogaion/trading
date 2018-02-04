<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/3/2018
 * Time: 8:54 PM
 */

namespace AppBundle\Domain\Service\Reporting;


use AppBundle\Domain\Model\Trading\Amount;
use AppBundle\Domain\Model\Trading\Principal;
use AppBundle\Domain\Model\Trading\Quote;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use AppBundle\Domain\Service\Trading\InterestService;

class InterestEvolution
{
    /**
     * @var Principal
     */
    protected $principal;

    /**
     * @var int
     */
    protected $buyVolume;

    /**
     * @var Amount
     */
    protected $buyValue;

    /**
     * @var \DateTime
     */
    protected $targetDate;

    /**
     * @var Quote
     */
    protected $startingQuote;

    public function getEvolution()
    {
//        $principalInterest = $this->principal->getInterest();
//
//        $interest = InterestService::makeInterest();
//        $interest->setPercent($this->principal->getInterest());
//
//        $fromDate = DateTimeInterval::getToday();
//        $toDate = $this->targetDate;
//        $interval = $fromDate->diff($toDate);
//        $interest->setInterval($interval);



//        $interest->setInterval()
//        return 1 + $this->principal->getInterest()
    }
}