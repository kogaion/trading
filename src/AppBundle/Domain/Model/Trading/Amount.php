<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/3/2018
 * Time: 8:13 PM
 */

namespace AppBundle\Domain\Model\Trading;


use AppBundle\Domain\Model\Util\InvalidOperationException;

class Amount
{
    /**
     * @var float
     */
    protected $value;
    /**
     * @var Currency
     */
    protected $currency;

    /**
     * @param Currency $currency
     * @return Amount
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @param float $value
     * @return Amount
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param Amount $amount
     * @return $this
     * @throws InvalidOperationException
     */
    public function add(Amount $amount)
    {
        if ($this->getCurrency() != $amount->getCurrency()) {
            throw new InvalidOperationException("Currency mismatch", InvalidOperationException::ERR_CURRENCY_MISMATCH);
        }

        $this->setValue($this->getValue() + $amount->getValue());
        return $this;
    }
}