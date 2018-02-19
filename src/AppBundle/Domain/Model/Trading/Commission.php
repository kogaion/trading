<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/19/2018
 * Time: 5:06 PM
 */

namespace AppBundle\Domain\Model\Trading;


class Commission
{
    protected $percent;
    protected $minAmount;
    
    public function getCommission($amount)
    {
        $commission = $amount * $this->percent / 100;
        if ($commission < $this->minAmount) {
            $commission = $this->minAmount;
        }
        
        return $commission;
    }
    
    /**
     * @param mixed $percent
     * @return Commission
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
        return $this;
    }
    
    /**
     * @param mixed $minAmount
     * @return Commission
     */
    public function setMinCommission($minAmount)
    {
        $this->minAmount = $minAmount;
        return $this;
    }
}