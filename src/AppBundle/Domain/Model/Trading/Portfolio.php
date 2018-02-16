<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 9:28 PM
 */

namespace AppBundle\Domain\Model\Trading;


class Portfolio
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
     * @var \DateTime
     */
    protected $acquisitionDate;
    /**
     * @var int
     */
    protected $balance;
    /**
     * @var double
     */
    protected $unitPrice;
    /**
     * @var double
     */
    protected $price;
    /**
     * @var double
     */
    protected $internalReturnRate;

    /**
     * @param double $unitPrice
     * @return Portfolio
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    /**
     * @param int $balance
     * @return Portfolio
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * @return double
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @return int
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @return double
     */
    public function getPrice()
    {
        return $this->unitPrice * $this->balance;
    }

    /**
     * @param \DateTime $acquisitionDate
     * @return Portfolio
     */
    public function setAcquisitionDate($acquisitionDate)
    {
        $this->acquisitionDate = $acquisitionDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getAcquisitionDate()
    {
        return $this->acquisitionDate;
    }
    
    /**
     * @param float $internalReturnRate
     * @return Portfolio
     */
    public function setInternalReturnRate($internalReturnRate)
    {
        $this->internalReturnRate = $internalReturnRate;
        return $this;
    }
    
    /**
     * @return float
     */
    public function getInternalReturnRate()
    {
        return $this->internalReturnRate;
    }
    
    /**
     * @param string $symbol
     * @return Portfolio
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }
}