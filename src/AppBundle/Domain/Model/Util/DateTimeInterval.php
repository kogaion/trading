<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/3/2018
 * Time: 10:46 PM
 */

namespace AppBundle\Domain\Model\Util;

/**
 * Class DateTimeInterval
 * @package AppBundle\Domain\Model\Util
 * @todo - join with Formatter ?
 */
class DateTimeInterval
{
    /**
     * @param \DateTime $fromDate
     * @param \DateInterval $interval
     * @return \DateInterval
     */
    public static function recalculate(\DateTime $fromDate, \DateInterval $interval)
    {
        $startDate = clone $fromDate;
        $endDate = clone $fromDate;
        $endDate = $endDate->add($interval);
        
        return $startDate->diff($endDate);
    }
    
    /**
     * @return \DateTime
     */
    public static function getToday()
    {
        return new \DateTime('today');
    }
    
    /**
     * @param string $dateString
     * @return \DateTime
     */
    public static function getDate($dateString = 'now')
    {
        return new \DateTime($dateString);
    }
}