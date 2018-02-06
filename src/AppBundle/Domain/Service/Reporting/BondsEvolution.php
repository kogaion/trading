<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/3/2018
 * Time: 8:54 PM
 */

namespace AppBundle\Domain\Service\Reporting;


use AppBundle\Domain\Model\Trading\Evolution;
use AppBundle\Domain\Model\Trading\Portfolio;
use AppBundle\Domain\Model\Trading\Principal;
use AppBundle\Domain\Model\Trading\PrincipalBonds;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use AppBundle\Domain\Service\Trading\AmountService;
use AppBundle\Domain\Service\Trading\EvolutionService;


class BondsEvolution
{
    /**
     * @var PrincipalBonds
     */
    protected $principal;

    /**
     * @var Portfolio
     */
    protected $portfolio;

    /**
     * @var AmountService
     */
    protected $amountService;

    public function __construct(AmountService $amountService)
    {
        $this->amountService = $amountService;
    }

    /**
     * @param PrincipalBonds $principal
     * @return BondsEvolution
     */
    public function setPrincipal($principal)
    {
        $this->principal = $principal;
        return $this;
    }

    /**
     * @param Portfolio $portfolio
     * @return BondsEvolution
     */
    public function setPortfolio($portfolio)
    {
        $this->portfolio = $portfolio;
        return $this;
    }

    /**
     * @param \DateInterval $interval
     * @return Evolution[]
     */
    public function getEvolution(\DateInterval $interval)
    {
        $fromDate = DateTimeInterval::getToday();
        $toDate = $this->principal->getMaturityDate();

        $return = [];

        $curDate = clone $fromDate;
        while (true) {
            $curDate = $curDate->add($interval);
            if ($curDate->format('U') > $toDate->format('U')) {
                $curDate = clone $toDate;
            }

            $return[] = $this->getInterestForInterval($fromDate, $curDate);

            if ($curDate->format('U') >= $toDate->format('U')) {
                break;
            }
        }

        return $return;
    }

    /**
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     * @return Evolution
     */
    protected function getInterestForInterval($fromDate, $toDate)
    {
        $amountFromInterest = $this->amountService->getAmountInterestForInterval(
            $this->portfolio->getPrice(),
            $this->principal->getInterest(),
            $fromDate->diff($toDate)
        );

        return EvolutionService::makeEvolution($toDate, $amountFromInterest);
    }
}