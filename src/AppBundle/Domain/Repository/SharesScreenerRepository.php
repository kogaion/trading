<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/17/2018
 * Time: 10:50 PM
 */

namespace AppBundle\Domain\Repository;


use AppBundle\Domain\Model\Crawling\SharesScreener;

interface SharesScreenerRepository
{
    /**
     * @param SharesScreener[] $shares
     * @return bool
     */
    public function storeShares(array $shares);
    
    /**
     * @return SharesScreener[]
     */
    public function loadShares();
    
    /**
     * @param $symbol
     * @return SharesScreener
     */
    public function loadShare($symbol);
}