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
     * @var AmountService
     */
    protected $amountService;

    /**
     * InterestService constructor.
     * @param AmountService $amountService
     */
    public function __construct(AmountService $amountService)
    {
        $this->amountService = $amountService;
    }

    /**
     * @param float $percent
     * @param \DateInterval $interval
     * @return Interest
     */
    public function makeInterest($percent, \DateInterval $interval)
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
        $evaluatedInterest = clone $interest;
        $evaluatedInterest->setInterval($evaluationInterval);

        return $this->amountService->makeAmount(
            $evaluatedInterest->getPercent() * $amount->getValue() / 100,
            $amount->getCurrency()
        );
    }
}