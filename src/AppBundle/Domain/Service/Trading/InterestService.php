<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 12:34 PM
 */

namespace AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\Interest;

class InterestService
{
    /**
     * @param int $percent
     * @param \DateInterval $interval
     * @return Interest
     */
    public static function makeInterest($percent, \DateInterval $interval)
    {
        return (new Interest())->setPercent($percent)->setInterval($interval);
    }
}