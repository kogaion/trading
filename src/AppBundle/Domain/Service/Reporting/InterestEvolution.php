<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/3/2018
 * Time: 8:54 PM
 */

namespace AppBundle\Domain\Service\Reporting;


use AppBundle\Domain\Model\Trading\Amount;
use AppBundle\Domain\Model\Trading\Principal;
use AppBundle\Domain\Model\Trading\Quote;
use Symfony\Component\Validator\Constraints\DateTime;

class InterestEvolution
{
    /**
     * @var Principal
     */
    protected $principal;

    /**
     * @var int
     */
    protected $buyVolume;

    /**
     * @var Amount
     */
    protected $buyValue;

    /**
     * @var DateTime
     */
    protected $targetDate;

    /**
     * @var Quote
     */
    protected $startingQuote;

    public function getEvolution()
    {
//        return $this->principal->
    }
}