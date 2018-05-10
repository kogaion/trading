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
use AppBundle\Domain\Model\Trading\Bond;
use AppBundle\Domain\Service\Trading\InterestService;
use AppBundle\Domain\Service\Trading\EvolutionService;
use AppBundle\Domain\Service\Trading\PortfolioService;


class BondsEvolutionService
{
    /**
     * @var Bond
     */
    protected $principal;

    /**
     * @var Portfolio
     */
    protected $portfolio;

    /**
     * @var InterestService
     */
    protected $interestService;
    /**
     * @var EvolutionService
     */
    protected $evolutionService;
    /**
     * @var PortfolioService
     */
    protected $portfolioService;

    /**
     * BondsEvolutionService constructor.
     * @param InterestService $interestService
     * @param EvolutionService $evolutionService
     * @param PortfolioService $portfolioService
     */
    public function __construct(InterestService $interestService, EvolutionService $evolutionService, PortfolioService $portfolioService)
    {
        $this->interestService = $interestService;
        $this->evolutionService = $evolutionService;
        $this->portfolioService = $portfolioService;
    }

    /**
     * @param Bond $principal
     * @return BondsEvolutionService
     */
    public function setPrincipal($principal)
    {
        $this->principal = $principal;
        return $this;
    }

    /**
     * @param Portfolio $portfolio
     * @return BondsEvolutionService
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
        $fromDate = clone $this->portfolio->getAcquisitionDate();
        $toDate = clone $this->principal->getMaturityDate();

        $return = [];

        $curDate = clone $fromDate;
        while (true) {
            $amount = $this->getEvolutionForInterval($fromDate, $curDate);
            $return[] = $this->evolutionService->makeEvolution(clone $curDate, $amount);

            $curDate = $curDate->add($interval);
            if ($curDate->format('U') >= $toDate->format('U')) {
                $curDate = clone $toDate;

                $amount = $this->getEvolutionForInterval($fromDate, $curDate);
                $return[] = $this->evolutionService->makeEvolution(clone $curDate, $amount);

                break;
            }
        }

        return $return;
    }

    /**
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     * @return double
     */
    protected function getInterestForInterval(\DateTime $fromDate, \DateTime $toDate)
    {
        $amountFromInterest = $this->interestService->getInterestForInterval(
            $this->getPrincipalPortfolio()->getPrice(),
            $this->principal->getInterest(),
            $fromDate,
            $toDate
        );

        return $amountFromInterest;
    }

    /**
     * @return Portfolio
     */
    protected function getPrincipalPortfolio()
    {
        return $this->portfolioService->makePortfolio(
            $this->principal->getSymbol(),
            $this->portfolio->getBalance(),
            $this->principal->getFaceValue(),
            $this->portfolio->getAcquisitionDate()
        );
    }

    /**
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     * @return double
     */
    protected function getEvolutionForInterval(\DateTime $fromDate, \DateTime $toDate)
    {
        $interestAmount = $this->getInterestForInterval($fromDate, $toDate);
        $principalAmount = $this->getPrincipalPortfolio()->getPrice();
        $acquisitionAmount = $this->portfolio->getPrice();
        
        return $principalAmount + $interestAmount - $acquisitionAmount;
    }
}