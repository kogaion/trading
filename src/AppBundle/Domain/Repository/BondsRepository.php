<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/16/2018
 * Time: 7:54 PM
 */

namespace AppBundle\Domain\Repository;


use AppBundle\Domain\Model\Trading\Bond;

interface BondsRepository
{
    /**
     * @param $symbol
     * @return Bond
     */
    public function loadBond($symbol);
    
    /**
     * @param Bond $bond
     * @return bool
     */
    public function storeBond(Bond $bond);
    
    /**
     * @return Bond[]
     */
    public function loadBonds();
}