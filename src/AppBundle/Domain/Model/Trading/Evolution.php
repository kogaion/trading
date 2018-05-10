<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 11:52 PM
 */

namespace AppBundle\Domain\Model\Trading;


class Evolution
{
    /**
     * @var \DateTime
     */
    protected $date;
    
    /**
     * @var float
     */
    protected $value;
    
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
     * @param float $value
     * @return Evolution
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
}