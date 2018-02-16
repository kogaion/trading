<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/15/2018
 * Time: 8:27 PM
 */

namespace AppBundle\Domain\Model\Crawling;


use AppBundle\Domain\Model\Util\Formatter;

class BondsScreener
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $symbol;
    /**
     * @var double
     */
    protected $bid;
    /**
     * @var int
     */
    protected $bidQty;
    /**
     * @var double
     */
    protected $ask;
    /**
     * @var int
     */
    protected $askQty;
    /**
     * @var double
     */
    protected $dirtyPrice;
    /**
     * @var double
     */
    protected $YTM;
    /**
     * @var int
     */
    protected $spreadDays;
    
    /**
     * @var \DateTime
     */
    protected $date;
    /**
     * @var \DateTime
     */
    protected $screenDate;
    
    /**
     * @param mixed $ask
     * @return BondsScreener
     */
    public function setAsk($ask)
    {
        $this->ask = Formatter::toDouble($ask);
        return $this;
    }
    
    /**
     * @param mixed $askQty
     * @return BondsScreener
     */
    public function setAskQty($askQty)
    {
        $this->askQty = Formatter::toInt($askQty);
        return $this;
    }
    
    /**
     * @param mixed $dirtyPrice
     * @return BondsScreener
     */
    public function setDirtyPrice($dirtyPrice)
    {
        $this->dirtyPrice = Formatter::toDouble($dirtyPrice);
        return $this;
    }
    
    /**
     * @param mixed $YTM
     * @return BondsScreener
     */
    public function setYTM($YTM)
    {
        $this->YTM = Formatter::toDouble($YTM);
        return $this;
    }
    
    /**
     * @param mixed $spreadDays
     * @return BondsScreener
     */
    public function setSpreadDays($spreadDays)
    {
        $this->spreadDays = Formatter::toInt($spreadDays);
        return $this;
    }
    
    /**
     * @param mixed $symbol
     * @return BondsScreener
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
        return $this;
    }
    
    /**
     * @param mixed $bid
     * @return BondsScreener
     */
    public function setBid($bid)
    {
        $this->bid = Formatter::toDouble($bid);
        return $this;
    }
    
    /**
     * @param mixed $bidQty
     * @return BondsScreener
     */
    public function setBidQty($bidQty)
    {
        $this->bidQty = Formatter::toInt($bidQty);
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
     * @return BondsScreener
     */
    public function setDate($date)
    {
        $this->date = Formatter::toDateTime($date);
        $this->setScreenDate(clone $this->date);
        return $this;
    }
    
    /**
     * @param int $id
     * @return BondsScreener
     */
    public function setId($id)
    {
        $this->id = Formatter::toInt($id);
        return $this;
    }
    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return float
     */
    public function getBid()
    {
        return $this->bid;
    }
    
    /**
     * @return int
     */
    public function getBidQty()
    {
        return $this->bidQty;
    }
    
    /**
     * @return float
     */
    public function getAsk()
    {
        return $this->ask;
    }
    
    /**
     * @param \DateTime $screenDate
     * @return BondsScreener
     */
    public function setScreenDate($screenDate)
    {
        $this->screenDate = Formatter::toDateTime($screenDate);
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getScreenDate()
    {
        return $this->screenDate;
    }
    
    /**
     * @return int
     */
    public function getSpreadDays()
    {
        return $this->spreadDays;
    }
    
    /**
     * @return float
     */
    public function getDirtyPrice()
    {
        return $this->dirtyPrice;
    }
    
    /**
     * @return float
     */
    public function getYTM()
    {
        return $this->YTM;
    }
    
    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
    
    /**
     * @return int
     */
    public function getAskQty()
    {
        return $this->askQty;
    }
}