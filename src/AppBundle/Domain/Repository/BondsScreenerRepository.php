<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/15/2018
 * Time: 8:12 PM
 */

namespace AppBundle\Domain\Repository;


use AppBundle\Domain\Model\Crawling\BondsScreener;

interface BondsScreenerRepository
{
    /**
     * @param BondsScreener[] $bonds
     * @return bool
     */
    public function storeBonds(array $bonds);
    
    /**
     * @return BondsScreener[]
     */
    public function loadBonds();
    
    /**
     * @param $symbol
     * @return BondsScreener
     */
    public function loadBond($symbol);
}