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
     * @return Portfolio[]
     */
    public function loadPortfolios();
}