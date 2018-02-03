<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/3/2018
 * Time: 8:11 PM
 */

namespace AppBundle\Domain\Model\Trading;


class Principal
{
    /**
     * @var Amount
     */
    protected $unitValue;
    /**
     * @var \DateTime end date of this principal
     */
    protected $amortizationDate;

    /**
     * @var Interest
     */
    protected $interest;

    /**
     * @var string
     */
    protected $symbol;

    /**
     * @return Interest
     */
    public function getInterest()
    {
        return $this->interest;
    }
}