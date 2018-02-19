<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/19/2018
 * Time: 5:55 PM
 */

namespace AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\Commission;

class CommissionService
{
    /**
     * @param $commissionPercent
     * @param $minCommission
     * @return Commission
     */
    public function makeCommission($commissionPercent, $minCommission)
    {
        return (new Commission())->setPercent($commissionPercent)->setMinCommission($minCommission);
    }
    
    /**
     * @param $price
     * @return float
     */
    public function getBondsCommission($price)
    {
        return $this->makeCommission(0.2 + 0.02, 1.9)->getCommission($price);
    }
    
    
    /**
     * @param $price
     * @return float
     */
    public function getSharesCommission($price)
    {
        return $this->makeCommission(0.65 + 0.06, 1.9)->getCommission($price);
    }
    
    /**
     * @param $price
     * @return float
     */
    public function getETFCommission($price)
    {
        return $this->makeCommission(0.65 + 0.04, 1.9)->getCommission($price);
    }
}