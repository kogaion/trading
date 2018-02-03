<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/3/2018
 * Time: 8:13 PM
 */

namespace AppBundle\Domain\Model\Trading;


use Symfony\Component\Validator\Constraints\Currency;

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


}