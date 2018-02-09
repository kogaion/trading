<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 11:52 PM
 */

namespace AppBundle\Domain\Model\Trading;


use AppBundle\Domain\Model\Util\DateTimeInterval;

class Evolution
{
    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @var Amount
     */
    protected $amount;

    /**
     * @param \DateTime $date
     * @return Evolution
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param Amount $amount
     * @return Evolution
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return Amount
     */
    public function getAmount()
    {
        return $this->amount;
    }
}