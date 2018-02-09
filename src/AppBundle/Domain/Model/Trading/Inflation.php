<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/9/2018
 * Time: 8:43 PM
 */

namespace AppBundle\Domain\Model\Trading;


class Inflation
{
    /**
     * @var float
     */
    protected $ratio;
    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @param float $ratio
     * @return Inflation
     */
    public function setRatio($ratio)
    {
        $this->ratio = $ratio;
        return $this;
    }

    /**
     * @return float
     */
    public function getRatio()
    {
        return $this->ratio;
    }

    /**
     * @param \DateTime $date
     * @return Inflation
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
}