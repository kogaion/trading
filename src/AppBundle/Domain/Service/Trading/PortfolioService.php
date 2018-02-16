<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 9:36 PM
 */

namespace AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Crawling\BondsScreener;
use AppBundle\Domain\Model\Trading\Portfolio;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use AppBundle\Domain\Repository\BondsScreenerRepository;
use AppBundle\Domain\Repository\PortfolioRepository;

class PortfolioService
{
    /**
     * @var BondsScreenerRepository
     */
    private $bondsScreenerRepository;
    /**
     * @var PortfolioRepository
     */
    private $portfolioRepository;
    
    public function __construct(PortfolioRepository $portfolioRepository, BondsScreenerRepository $bondsScreenerRepository)
    {
        $this->bondsScreenerRepository = $bondsScreenerRepository;
        $this->portfolioRepository = $portfolioRepository;
    }
    
    /**
     * @param string $symbol
     * @param int $balance
     * @param double $unitPrice
     * @param \DateTime $acquisitionDate
     * @return Portfolio
     */
    public function makePortfolio($symbol, $balance, $unitPrice, \DateTime $acquisitionDate)
    {
        return (new Portfolio())
            ->setSymbol($symbol)
            ->setUnitPrice($unitPrice)
            ->setBalance($balance)
            ->setAcquisitionDate($acquisitionDate);
    }

    /**
     * @return Portfolio[]
     */
    public function listPortfolios()
    {
        // get existing bonds
        return $this->portfolioRepository->loadPortfolios();
    }

    /**
     * @param $symbol
     * @return Portfolio
     */
    public function buildPortfolio($symbol)
    {
        return $this->portfolioRepository->loadPortfolio($symbol);
    }
    
    /**
     * @param $symbol
     * @return Portfolio|null
     */
    public function buildVirtualPortfolio($symbol)
    {
        $bonds = $this->bondsScreenerRepository->loadBond($symbol);
        if ($bonds instanceof BondsScreener && $bonds->getSymbol() == $symbol) {
            return $this->makePortfolio(
                $symbol,
                $bonds->getAskQty(),
                $bonds->getDirtyPrice(), // @todo -> add commission !!!
                DateTimeInterval::getToday()
            );
        }
        return null;
    }
}