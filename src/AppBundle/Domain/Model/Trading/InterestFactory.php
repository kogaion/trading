<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/3/2018
 * Time: 11:52 PM
 */

namespace AppBundle\Domain\Model\Trading;


class InterestFactory
{
    public static function makeInterest($percent = 0, \DateInterval $interval = null)
    {
        return (new Interest())->setPercent($percent)->setInterval($interval);
    }
}