<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 9:28 PM
 */

namespace AppBundle\Domain\Model\Trading;


use AppBundle\Domain\Service\Trading\AmountService;

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
     * @var Amount
     */
    protected $price;

    /**
     * @param Amount $acquisitionPrice
     * @return Portfolio
     */
    public function setAcquisitionPrice(Amount $acquisitionPrice)
    {
        $this->acquisitionPrice = $acquisitionPrice;
        $this->setPrice();
        return $this;
    }

    /**
     * @param int $balance
     * @return Portfolio
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        $this->setPrice();
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
        return $this->price;
    }

    protected function setPrice()
    {
        if (null !== $this->acquisitionPrice) {
            $this->price = AmountService::makeAmount(
                $this->getAcquisitionPrice()->getValue() * $this->getBalance(),
                $this->getAcquisitionPrice()->getCurrency()
            );
        }
        return $this;
    }
}