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

class PortfolioService
{
    /**
     * @param int $balance
     * @param Amount $acquisitionAmount
     * @return Portfolio
     */
    public static function makePortfolio($balance, Amount $acquisitionAmount)
    {
        return (new Portfolio())->setAcquisitionPrice($acquisitionAmount)->setBalance($balance);
    }
}