<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/15/2018
 * Time: 7:00 PM
 */

namespace AppBundle\Domain\Model\Util;


class Formatter
{
    public static function toDouble($param, $decPoint = '.', $thousandSep = ',')
    {
        if (!is_double($param)) {
            $param = floatval(str_replace([$thousandSep, $decPoint], ['', '.'], trim($param)));
        }
        return (double) $param;
    }
    
    public static function toInt($param, $decPoint = '.', $thousandSep = ',')
    {
        if (!is_int($param)) {
            $param = self::toDouble($param, $decPoint, $thousandSep);
        }
        return (int) $param;
    }
    
    public static function toDateTime($param, $format = 'm/d/Y')
    {
        if (!($param instanceof \DateTime)) {
            $param = \DateTime::createFromFormat($format, trim($param));
            if ($param instanceof \DateTime && false === stripos($format, 'h')) {
                $param->setTime(0, 0, 0);
            }
        }
        return $param;
    }
}