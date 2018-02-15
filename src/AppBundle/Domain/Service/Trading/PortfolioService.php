<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 9:36 PM
 */

namespace AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\Amount;
use AppBundle\Domain\Model\Trading\Portfolio;
use AppBundle\Domain\Model\Util\DateTimeInterval;

class PortfolioService
{
    /**
     * @var AmountService
     */
    protected $amountService;

    public function __construct(AmountService $amountService)
    {
        $this->amountService = $amountService;
    }

    /**
     * @param int $balance
     * @param Amount $unitPrice
     * @param \DateTime $acquisitionDate
     * @return Portfolio
     */
    public function makePortfolio($balance, Amount $unitPrice, \DateTime $acquisitionDate)
    {
        return (new Portfolio())->setUnitPrice($unitPrice)->setBalance($balance)->setAcquisitionDate($acquisitionDate);
    }

    /**
     * @param $principalSymbol
     * @return Portfolio
     * @todo Load from Repository
     */
    public function buildPortfolio($principalSymbol)
    {
        $principalPortfolio = $this->searchPortfolio($principalSymbol);
        return $this->makePortfolio(
            $principalPortfolio[0],
            $this->amountService->buildAmount($principalPortfolio[1], $principalPortfolio[2]),
            DateTimeInterval::getDate($principalPortfolio[3])
        );
    }

    /**
     * @return Portfolio[]
     */
    public function listPortfolio()
    {
        $portfolio = [];
        foreach ($this->loadPortfolio() as $symbol => $details) {
            $portfolio[$symbol] = $this->buildPortfolio($symbol);
        }
        return $portfolio;
    }

    /**
     * @param $principalSymbol
     * @return mixed
     * @todo load from Repository
     */
    protected function searchPortfolio($principalSymbol)
    {
        $portfolio = $this->loadPortfolio();
        if (!array_key_exists($principalSymbol, $portfolio)) {
            return [0, 0, CurrencyService::DEFAULT_CURRENCY, 'today'];
        }

        return $portfolio[$principalSymbol];
    }

    /**
     * @return array
     * @todo load from repository
     */
    protected function loadPortfolio()
    {
        return [
            'SBG20' => [100, 104.1836, 'LEI', 'today', 10.75],
            'ADRS18' => [28, 1096.6667, 'LEI', 'today'],
            'BNET19' => [5, 1048.3767, 'LEI', 'today', 6.22],
            'BNET22' => [40, 106.1521, 'LEI', 'today', 8.08],
            'CFS18' => [8, 1020.9075, 'LEI', '2018-15-02', 8.65],
            'FRU21' => [13, 105.6667, 'LEI', 'today', 7.76],
            'INV22' => [20, 97.0200, 'LEI', 'today'],
        ];
    }
}