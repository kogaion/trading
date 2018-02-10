<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/3/2018
 * Time: 8:54 PM
 */

namespace AppBundle\Domain\Service\Reporting;


use AppBundle\Domain\Model\Trading\Amount;
use AppBundle\Domain\Model\Trading\Evolution;
use AppBundle\Domain\Model\Trading\Portfolio;
use AppBundle\Domain\Model\Trading\PrincipalBonds;
use AppBundle\Domain\Service\Trading\AmountService;
use AppBundle\Domain\Service\Trading\InterestService;
use AppBundle\Domain\Service\Trading\EvolutionService;
use AppBundle\Domain\Service\Trading\PortfolioService;


class BondsEvolutionService
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
    /**
     * @var InterestService
     */
    protected $interestService;
    /**
     * @var EvolutionService
     */
    private $evolutionService;
    /**
     * @var PortfolioService
     */
    private $portfolioService;

    /**
     * BondsEvolutionService constructor.
     * @param AmountService $amountService
     * @param InterestService $interestService
     * @param EvolutionService $evolutionService
     * @param PortfolioService $portfolioService
     */
    public function __construct(AmountService $amountService, InterestService $interestService, EvolutionService $evolutionService, PortfolioService $portfolioService)
    {
        $this->amountService = $amountService;
        $this->interestService = $interestService;
        $this->evolutionService = $evolutionService;
        $this->portfolioService = $portfolioService;
    }

    /**
     * @param PrincipalBonds $principal
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
     * @param \DateTime $fromDate
     * @param \DateInterval $interval
     * @return Evolution[]
     */
    public function getEvolution(\DateTime $fromDate, \DateInterval $interval)
    {
        $toDate = clone $this->principal->getMaturityDate();

        $return = [];

        $curDate = clone $fromDate;
        while (true) {
            $amount = $this->getEvolutionAmountForInterval($fromDate, $curDate);
            $return[] = $this->evolutionService->makeEvolution(clone $curDate, $amount->getValue());

            $curDate = $curDate->add($interval);
            if ($curDate->format('U') >= $toDate->format('U')) {
                $curDate = clone $toDate;

                $amount = $this->getEvolutionAmountForInterval($fromDate, $curDate);
                $return[] = $this->evolutionService->makeEvolution(clone $curDate, $amount->getValue());

                break;
            }
        }

        return $return;
    }

    /**
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     * @return Amount
     */
    protected function getInterestForInterval(\DateTime $fromDate, \DateTime $toDate)
    {
        $amountFromInterest = $this->interestService->getInterestForInterval(
            $this->getPrincipalPortfolio()->getPrice(),
            $this->principal->getInterest(),
            $fromDate->diff($toDate)
        );

        return $amountFromInterest;
    }

    /**
     * @return Portfolio
     */
    protected function getPrincipalPortfolio()
    {
        return $this->portfolioService->makePortfolio(
            $this->portfolio->getBalance(),
            $this->principal->getFaceValue(),
            $this->portfolio->getAcquisitionDate()
        );
    }

    /**
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     * @return Amount
     */
    protected function getEvolutionAmountForInterval(\DateTime $fromDate, \DateTime $toDate)
    {
        $interestAmount = $this->getInterestForInterval($fromDate, $toDate);
        $principalAmount = $this->getPrincipalPortfolio()->getPrice();
        $acquisitionAmount = $this->portfolio->getPrice();

        return $principalAmount->add($interestAmount)->sub($acquisitionAmount);
    }
}