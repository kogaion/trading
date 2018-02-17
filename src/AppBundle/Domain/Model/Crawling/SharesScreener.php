<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/17/2018
 * Time: 10:33 PM
 */

namespace AppBundle\Domain\Model\Crawling;


use AppBundle\Domain\Model\Util\Formatter;

class SharesScreener
{
    protected $id;
    protected $symbol;
    protected $lastPrice;
    protected $variation;
    protected $referenceDate;
    protected $referencePrice;
    protected $bid;
    protected $ask;
    protected $date;
    protected $screenDate;
    
    /**
     * @param mixed $id
     * @return SharesScreener
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * @param mixed $symbol
     * @return SharesScreener
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
        return $this;
    }
    
    /**
     * @param mixed $lastPrice
     * @return SharesScreener
     */
    public function setLastPrice($lastPrice)
    {
        $this->lastPrice = Formatter::toDouble($lastPrice);
        return $this;
    }
    
    /**
     * @param mixed $variation
     * @return SharesScreener
     */
    public function setVariation($variation)
    {
        $this->variation = Formatter::toDouble($variation);
        return $this;
    }
    
    /**
     * @param mixed $referenceDate
     * @return SharesScreener
     */
    public function setReferenceDate($referenceDate)
    {
        $this->referenceDate = Formatter::toDateTime($referenceDate);
        return $this;
    }
    
    /**
     * @param mixed $referencePrice
     * @return SharesScreener
     */
    public function setReferencePrice($referencePrice)
    {
        $this->referencePrice = Formatter::toDouble($referencePrice);
        return $this;
    }
    
    /**
     * @param mixed $bid
     * @return SharesScreener
     */
    public function setBid($bid)
    {
        $this->bid = Formatter::toDouble($bid);
        return $this;
    }
    
    /**
     * @param mixed $ask
     * @return SharesScreener
     */
    public function setAsk($ask)
    {
        $this->ask = Formatter::toDouble($ask);
        return $this;
    }
    
    /**
     * @param mixed $date
     * @return SharesScreener
     */
    public function setDate($date)
    {
        $this->date = Formatter::toDateTime($date);
        $this->setScreenDate(clone $this->date);
        return $this;
    }
    
    /**
     * @param mixed $screenDate
     * @return SharesScreener
     */
    public function setScreenDate($screenDate)
    {
        $this->screenDate = Formatter::toDateTime($screenDate);
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
     * @return mixed
     */
    public function getLastPrice()
    {
        return $this->lastPrice;
    }
    
    /**
     * @return mixed
     */
    public function getVariation()
    {
        return $this->variation;
    }
    
    /**
     * @return mixed
     */
    public function getReferenceDate()
    {
        return $this->referenceDate;
    }
    
    /**
     * @return mixed
     */
    public function getReferencePrice()
    {
        return $this->referencePrice;
    }
    
    /**
     * @return mixed
     */
    public function getBid()
    {
        return $this->bid;
    }
    
    /**
     * @return mixed
     */
    public function getAsk()
    {
        return $this->ask;
    }
    
    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }
    
    /**
     * @return mixed
     */
    public function getScreenDate()
    {
        return $this->screenDate;
    }
}