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
    const INTEREST_TYPE_FIXED = 'fixed';
    const INTEREST_TYPE_VARIABLE = 'variable';
    
    /**
     * @var double
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
     * @var string
     */
    protected $interestType;
    
    /**
     * @var \DateInterval
     */
    protected $interestInterval;
    

    /**
     * @param double $faceValue
     * @return PrincipalBonds
     */
    public function setFaceValue($faceValue)
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

    /**
     * @return double
     */
    public function getFaceValue()
    {
        return $this->faceValue;
    }
    
    /**
     * @param string $interestType
     * @return PrincipalBonds
     */
    public function setInterestType($interestType)
    {
        if ($interestType == self::INTEREST_TYPE_FIXED || $interestType == self::INTEREST_TYPE_VARIABLE) {
            $this->interestType = $interestType;
        } else {
            $this->interestType = self::INTEREST_TYPE_FIXED;
        }
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getInterestType()
    {
        return $this->interestType;
    }
}