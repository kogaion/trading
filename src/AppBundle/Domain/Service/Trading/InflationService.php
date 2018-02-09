<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/9/2018
 * Time: 8:15 PM
 */

namespace AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\Inflation;
use AppBundle\Domain\Model\Util\DateTimeInterval;

class InflationService
{
    const DATE_FORMAT = 'Y-m-d';
    /**
     * @var array
     * @todo Extract from repository
     */
    protected static $ratios = [
        '2018-01-01' => 2,
        '2018-02-01' => 3,
        '2018-05-01' => 3.6,
        '2018-08-01' => 4,
    ];

    /**
     * @param $ratio
     * @param \DateTime $date
     * @return Inflation
     */
    public static function makeInflation($ratio, \DateTime $date)
    {
        return (new Inflation())->setRatio($ratio)->setDate($date);
    }

    /**
     * @param \DateTime $forDate
     * @return Inflation
     * @todo load from Repository
     */
    public static function buildInflation(\DateTime $forDate)
    {
        $dateFormatted = $forDate->format(self::DATE_FORMAT);
        if (array_key_exists($dateFormatted, self::$ratios)) {
            return self::$ratios[$dateFormatted];
        }

        $inflating = self::$ratios;
        $inflating[$dateFormatted] = 0;
        ksort($inflating);

        $datesFormatted = array_keys($inflating);
        $ratio = 0;
        for ($idx = 0, $cnt = count($datesFormatted); $idx < $cnt; $idx++) {
            if ($datesFormatted[$idx] == $dateFormatted) {
                if (isset($datesFormatted[$idx - 1])) {
                    $ratio = $inflating[$datesFormatted[$idx - 1]];
                } else if (isset($datesFormatted[$idx + 1])) {
                    $ratio = $inflating[$datesFormatted[$idx + 1]];
                }
            }
        }

        return self::makeInflation($ratio, clone $forDate);
    }

    /**
     * @return Inflation[]
     * @todo extract from Repository
     */
    public function listInflating()
    {
        $inflating = [];
        foreach (self::$ratios as $ratioDate => $ratioValue) {
            $inflating[] = self::makeInflation($ratioValue, DateTimeInterval::getDate($ratioDate));
        }
        return $inflating;
    }
}