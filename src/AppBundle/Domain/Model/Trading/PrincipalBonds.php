<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 11:03 PM
 */

namespace AppBundle\Domain\Model\Trading;


use AppBundle\Domain\Model\Util\Formatter;

class PrincipalBonds extends Principal
{
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
     * @param double $faceValue
     * @return PrincipalBonds
     */
    public function setFaceValue($faceValue)
    {
        $this->faceValue = Formatter::toDouble($faceValue);
        return $this;
    }
    
    /**
     * @param \DateTime $maturityDate
     * @return PrincipalBonds
     */
    public function setMaturityDate($maturityDate)
    {
        $this->maturityDate = Formatter::toDateTime($maturityDate);
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
    
    
}