<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/3/2018
 * Time: 8:07 PM
 */

namespace AppBundle\Domain\Model\Trading;

use Symfony\Component\Validator\Constraints\DateTime;

class Quote
{
    /**
     * @var int
     */
    protected $askVolume;
    /**
     * @var Amount
     */
    protected $askValue;

    /**
     * @var int
     */
    protected $bidVolume;
    /**
     * @var Amount
     */
    protected $bidValue;

    /**
     * @var DateTime
     */
    protected $transactionDate;

    /**
     * @return DateTime
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }
}