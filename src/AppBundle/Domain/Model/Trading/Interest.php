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
    protected $percent;

    /**
     * @var \DateInterval
     */
    protected $interval;

    /**
     * Interest constructor.
     * @param float $percent
     * @param \DateInterval|null $interval
     */
    public function __construct($percent = 0.00, \DateInterval $interval = null)
    {
        $this->percent = max(min(round($percent, 2), 100.00), 0.00);
        $this->interval = $interval;
    }

    /**
     * @param int $precision
     * @return float
     */
    public function getDailyInterest($precision = 2)
    {
        $dateInterval = DateTimeInterval::recalculate($this->interval);
        return round($this->percent / $dateInterval->format("%a"), $precision);
    }
}