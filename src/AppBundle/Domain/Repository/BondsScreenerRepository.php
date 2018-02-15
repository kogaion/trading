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
     * @param BondsScreener[] $bs
     * @return bool
     */
    public function storeBulk(array $bs);
}