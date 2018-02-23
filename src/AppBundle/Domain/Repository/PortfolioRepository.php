<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/16/2018
 * Time: 5:33 PM
 */

namespace AppBundle\Domain\Repository;


use AppBundle\Domain\Model\Trading\Portfolio;

interface PortfolioRepository
{
    /**
     * @param PortfolioSearch|null $search
     * @return Portfolio[]
     */
    public function loadPortfolios(PortfolioSearch $search = null);
    
    /**
     * @param $symbol
     * @return Portfolio
     */
//    public function loadPortfolio($symbol);
}