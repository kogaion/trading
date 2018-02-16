<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/16/2018
 * Time: 7:54 PM
 */

namespace AppBundle\Domain\Repository;


use AppBundle\Domain\Model\Trading\PrincipalBonds;

interface BondsRepository
{
    /**
     * @param $symbol
     * @return PrincipalBonds
     */
    public function loadBond($symbol);
    
    /**
     * @param PrincipalBonds $bond
     * @return bool
     */
    public function storeBond(PrincipalBonds $bond);
    
    /**
     * @return PrincipalBonds[]
     */
    public function loadBonds();
}