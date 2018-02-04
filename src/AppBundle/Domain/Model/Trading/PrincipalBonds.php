<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 11:03 PM
 */

namespace AppBundle\Domain\Model\Trading;


class PrincipalBonds extends Principal
{
    /**
     * @var Amount
     */
    protected $faceValue;

    /**
     * @var \DateTime
     */
    protected $maturityDate;

    /**
     * @var Interest
     */
    protected $interest;

    /**
     * @param Amount $faceValue
     * @return PrincipalBonds
     */
    public function setFaceValue(Amount $faceValue)
    {
        $this->faceValue = $faceValue;
        return $this;
    }

    /**
     * @param \DateTime $maturityDate
     * @return PrincipalBonds
     */
    public function setMaturityDate($maturityDate)
    {
        $this->maturityDate = $maturityDate;
        return $this;
    }

    /**
     * @param Interest $interest
     * @return PrincipalBonds
     */
    public function setInterest($interest)
    {
        $this->interest = $interest;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getMaturityDate()
    {
        return $this->maturityDate;
    }

    /**
     * @return Interest
     */
    public function getInterest()
    {
        return $this->interest;
    }
}