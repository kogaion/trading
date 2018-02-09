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
     * @var \DateTime
     */
    protected $acquisitionDate;
    /**
     * @var int
     */
    protected $balance;
    /**
     * @var Amount
     */
    protected $unitPrice;
    /**
     * @var Amount
     */
    protected $price;

    /**
     * @param Amount $unitPrice
     * @return Portfolio
     */
    public function setUnitPrice(Amount $unitPrice)
    {
        $this->unitPrice = $unitPrice;
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
     * @return Amount
     */
    public function getPrice()
    {
        return $this->price;
    }

    protected function setPrice()
    {
        if (null !== $this->unitPrice) {
            $this->price = AmountService::makeAmount(
                $this->getUnitPrice()->getValue() * $this->getBalance(),
                $this->getUnitPrice()->getCurrency()
            );
        }
        return $this;
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
}