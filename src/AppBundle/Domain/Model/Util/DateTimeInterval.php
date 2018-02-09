<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/3/2018
 * Time: 10:46 PM
 */

namespace AppBundle\Domain\Model\Util;



class DateTimeInterval
{
    /**
     * @param \DateInterval $interval
     * @return \DateInterval
     */
    public static function recalculate(\DateInterval $interval)
    {
        $startDate = static::getToday();
        $endDate = static::getToday();
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