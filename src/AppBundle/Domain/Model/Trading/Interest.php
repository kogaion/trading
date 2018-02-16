<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/3/2018
 * Time: 9:10 PM
 */

namespace AppBundle\Domain\Model\Trading;


class Interest
{
    /**
     * @var double
     */
    protected $percent;
    /**
     * @var \DateInterval
     */
    protected $interval;

    /**
     * @param double $percent
     * @return Interest
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
        return $this;
    }

    /**
     * @param \DateInterval $interval
     * @return Interest
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
        return $this;
    }

    /**
     * @return float
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @return \DateInterval
     */
    public function getInterval()
    {
        return $this->interval;
    }
}