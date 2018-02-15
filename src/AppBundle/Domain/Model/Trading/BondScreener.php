<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/15/2018
 * Time: 4:18 PM
 */

namespace AppBundle\Domain\Model\Trading;


class BondScreener
{
    protected $symbol;
    protected $bid;
    protected $bidQty;
    protected $ask;
    protected $askQty;
    protected $dirtyPrice;
    protected $YTM;
    protected $maturityDate;
    protected $spreadDays;
    protected $interest;
    protected $date;
    
    /**
     * @param mixed $ask
     * @return BondScreener
     */
    public function setAsk($ask)
    {
        $this->ask = $ask;
        return $this;
    }
    
    /**
     * @param mixed $askQty
     * @return BondScreener
     */
    public function setAskQty($askQty)
    {
        $this->askQty = $askQty;
        return $this;
    }
    
    /**
     * @param mixed $dirtyPrice
     * @return BondScreener
     */
    public function setDirtyPrice($dirtyPrice)
    {
        $this->dirtyPrice = $dirtyPrice;
        return $this;
    }
    
    /**
     * @param mixed $YTM
     * @return BondScreener
     */
    public function setYTM($YTM)
    {
        $this->YTM = $YTM;
        return $this;
    }
    
    /**
     * @param mixed $maturityDate
     * @return BondScreener
     */
    public function setMaturityDate($maturityDate)
    {
        $this->maturityDate = $maturityDate;
        return $this;
    }
    
    /**
     * @param mixed $spreadDays
     * @return BondScreener
     */
    public function setSpreadDays($spreadDays)
    {
        $this->spreadDays = $spreadDays;
        return $this;
    }
    
    /**
     * @param mixed $interest
     * @return BondScreener
     */
    public function setInterest($interest)
    {
        $this->interest = $interest;
        return $this;
    }
    
    /**
     * @param mixed $symbol
     * @return BondScreener
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
        return $this;
    }
    
    /**
     * @param mixed $bid
     * @return BondScreener
     */
    public function setBid($bid)
    {
        $this->bid = $bid;
        return $this;
    }
    
    /**
     * @param mixed $bidQty
     * @return BondScreener
     */
    public function setBidQty($bidQty)
    {
        $this->bidQty = $bidQty;
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getSymbol()
    {
        return $this->symbol;
    }
    
    /**
     * @param mixed $date
     * @return BondScreener
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }
    
    
}