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
    const DEFAULT_CURRENCY = 'LEI';

    /**
     * @var array
     * @todo load from repository
     */
    protected static $bondsPortfolio = [
        'SBG20' => [100, 104.5, 'LEI', 'today'],
        'ADRS18' => [27, 110, 'LEI', 'today'],
        'BNET19' => [11, 104, 'LEI', 'today'],
        'CFS18' => [1, 100.03, 'LEI', 'today'],
        'FRU21' => [500, 104, 'LEI', 'today'],
        'INV22' => [20, 105, 'LEI', 'today'],
    ];

    /**
     * @param int $balance
     * @param Amount $unitPrice
     * @param \DateTime $acquisitionDate
     * @return Portfolio
     */
    public static function makePortfolio($balance, Amount $unitPrice, \DateTime $acquisitionDate)
    {
        return (new Portfolio())->setUnitPrice($unitPrice)->setBalance($balance)->setAcquisitionDate($acquisitionDate);
    }

    /**
     * @param $bondsSymbol
     * @return Portfolio
     * @todo Load from Repository
     */
    public function buildPortfolio($bondsSymbol)
    {
        if (!array_key_exists($bondsSymbol, self::$bondsPortfolio)) {
            return PortfolioService::makePortfolio(0, AmountService::buildAmount(0, self::DEFAULT_CURRENCY), DateTimeInterval::getToday());
        }

        $bondsPortfolio = self::$bondsPortfolio[$bondsSymbol];
        return PortfolioService::makePortfolio(
            $bondsPortfolio[0],
            AmountService::buildAmount($bondsPortfolio[1], $bondsPortfolio[2]),
            DateTimeInterval::getDate($bondsPortfolio[3])
        );
    }
}