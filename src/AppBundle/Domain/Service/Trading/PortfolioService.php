<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 9:36 PM
 */

namespace AppBundle\Domain\Service\Trading;


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
        $return = $this->portfolioRepository->loadPortfolios();
        
        $interestedInBonds = ['SBG20', 'ADRS18', 'BNET19', 'BNET22', 'CFS18', 'FRU21', 'INV22'];
        foreach ($interestedInBonds as $symbol) {
            $return[] = $this->buildVirtualBondPortfolio($symbol);
        }
        
        $interestedInShares = [];
        foreach ($interestedInShares as $symbol) {
            $return[] = $this->buildVirtualSharePortfolio($symbol);
        }
        
        return $return;
    }

    /**
     * @param $symbol
     * @return Portfolio
     * @todo load from Repository
     */
    public function buildPortfolio($symbol)
    {
        $portfolio = $this->listPortfolios();
        foreach ($portfolio as $p) {
            if ($p->getSymbol() == $symbol) {
                return $p;
            }
        }
        
        $p = $this->buildVirtualBondPortfolio($symbol);
        if ($p !== null) {
            return $p;
        }
    
        $p = $this->buildVirtualSharePortfolio($symbol);
        if ($p !== null) {
            return $p;
        }
        
        // @todo !!! the same symbol can be both a BOND and a SHARE ... which one gets first?
        
        return $this->makePortfolio($symbol, 0, 0, DateTimeInterval::getToday());
    }
    
    /**
     * @param $symbol
     * @return Portfolio|null
     */
    protected function buildVirtualBondPortfolio($symbol)
    {
        $bonds = $this->bondsScreenerRepository->loadBond($symbol);
        if ($bonds->getSymbol() == $symbol) {
            return $this->makePortfolio(
                $symbol,
                $bonds->getAskQty(),
                $bonds->getDirtyPrice(), // @todo -> add commission !!!
                DateTimeInterval::getToday()
            );
        }
        return null;
    }
    
    /**
     * @param $symbol
     * @return Portfolio|null
     */
    protected function buildVirtualSharePortfolio($symbol)
    {
        return null;
    }
}