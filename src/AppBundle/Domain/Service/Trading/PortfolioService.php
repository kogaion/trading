<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 9:36 PM
 */

namespace AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Crawling\BondsScreener;
use AppBundle\Domain\Model\Trading\Commission;
use AppBundle\Domain\Model\Trading\Portfolio;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use AppBundle\Domain\Repository\BondsScreenerRepository;
use AppBundle\Domain\Repository\PortfolioRepository;
use AppBundle\Domain\Repository\PortfolioSearch;

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
    /**
     * @var CommissionService
     */
    private $commissionService;
    
    /**
     * PortfolioService constructor.
     * @param CommissionService $commissionService
     * @param PortfolioRepository $portfolioRepository
     * @param BondsScreenerRepository $bondsScreenerRepository
     */
    public function __construct(CommissionService $commissionService, PortfolioRepository $portfolioRepository, BondsScreenerRepository $bondsScreenerRepository)
    {
        $this->bondsScreenerRepository = $bondsScreenerRepository;
        $this->portfolioRepository = $portfolioRepository;
        $this->commissionService = $commissionService;
    }
    
    /**
     * @param string $symbol
     * @param int $balance
     * @param double $unitPrice
     * @param \DateTime|string $acquisitionDate
     * @return Portfolio
     */
    public function makePortfolio($symbol, $balance, $unitPrice, $acquisitionDate)
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
     * @return Portfolio[]
     */
    public function buildPortfolio($symbol)
    {
        $search = new PortfolioSearch();
        $search->symbol = $symbol;
        
        return $this->portfolioRepository->loadPortfolios($search);
    }
    
    /**
     * @param $symbol
     * @param float|null $price
     * @param string $startingDate
     * @return Portfolio|null
     */
    public function buildVirtualPortfolio($symbol, $price = null, $startingDate = null)
    {
        $bonds = $this->bondsScreenerRepository->loadBond($symbol);
        if ($bonds instanceof BondsScreener && $bonds->getSymbol() == $symbol) {
            $price = $price ?: $bonds->getDirtyPrice();
            return $this->makePortfolio(
                $symbol,
                $bonds->getAskQty(),
                $price + $this->commissionService->getBondsCommission($price),
                DateTimeInterval::getDate($startingDate ?: 'today')
            );
        }
        return null;
    }
    
    /**
     * @param Portfolio $p
     * @return bool
     */
    public function savePortfolio(Portfolio $p)
    {
        $search = new PortfolioSearch();
        $search->symbol = $p->getSymbol();
        $search->date = $p->getAcquisitionDate();
        
        $existingPortfolios = $this->portfolioRepository->loadPortfolios($search);
        if (count($existingPortfolios)) {
            return true;
        }
        
        return $this->portfolioRepository->storePortfolio($p);
    }
}