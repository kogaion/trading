<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/3/2018
 * Time: 9:10 PM
 */

namespace AppBundle\Domain\Model\Trading;


use AppBundle\Domain\Model\Util\DateTimeInterval;

class Interest
{
    /**
     * @var float
     */
    protected $percent = 0.00;

    /**
     * @var int
     */
    protected $percentPrecision = 2;
    /**
     * @var float
     */
    protected $percentMin = 0.0;
    /**
     * @var float
     */
    protected $percentMax = 100.0;

    /**
     * @var \DateInterval
     */
    protected $interval;

    /**
     * @param float $percent
     * @return Interest
     */
    public function setPercent($percent)
    {
        $this->percent = max(min(round($percent, $this->percentPrecision), $this->percentMax), $this->percentMin);
        return $this;
    }

    /**
     * @param \DateInterval $interval
     * @return Interest
     */
    public function setInterval($interval)
    {
        if (!empty($interval)) {
            $interval = DateTimeInterval::recalculate($interval);
        }

        if (empty($this->interval)) {
            $this->interval = $interval;
        } else if ($this->percent > 0) {
            $this->percent = $this->percent / $this->interval->format("%a") * $interval->format("%a");
            $this->interval = $interval;
        }

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