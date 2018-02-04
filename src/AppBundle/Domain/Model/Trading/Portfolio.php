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
    protected $balance;
    /**
     * @var Amount
     */
    protected $acquisitionPrice;

    /**
     * @param Amount $acquisitionPrice
     * @return Portfolio
     */
    public function setAcquisitionPrice(Amount $acquisitionPrice)
    {
        $this->acquisitionPrice = $acquisitionPrice;
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
     * @return Amount
     */
    public function getAcquisitionPrice()
    {
        return $this->acquisitionPrice;
    }

    /**
     * @return int
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @return Amount
     */
    public function getPrice()
    {
        $price = clone $this->acquisitionPrice;
        $price->setValue($this->getAcquisitionPrice()->getValue() * $this->getBalance());
        return $price;
    }
}