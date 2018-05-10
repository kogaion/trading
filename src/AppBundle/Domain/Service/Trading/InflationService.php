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
    /**
     * @param $ratio
     * @param \DateTime $date
     * @return Inflation
     */
    public function makeInflation($ratio, \DateTime $date)
    {
        return (new Inflation())->setRatio($ratio)->setDate($date);
    }
    
    /**
     * @param \DateTime $forDate
     * @return Inflation
     */
    public function buildInflation(\DateTime $forDate)
    {
        $inflating = $this->loadInflating();
        $dateFormatted = $forDate->format('U');
        
        if (array_key_exists($dateFormatted, $inflating)) {
            return $this->makeInflation($inflating[$dateFormatted], clone $forDate);
        }
        
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
        
        return $this->makeInflation($ratio, clone $forDate);
    }
    
    /**
     * @return Inflation[]
     */
    public function listInflating()
    {
        $inflating = [];
        foreach ($this->loadInflating() as $ratioDate => $ratioValue) {
            $inflating[] = $this->makeInflation($ratioValue, \DateTime::createFromFormat('U', $ratioDate));
        }
        return $inflating;
    }
    
    /**
     * @return array
     * @todo load from Repository
     */
    protected function loadInflating()
    {
        return [
            DateTimeInterval::getDate('2018-01-01')->format('U') => 2,
            DateTimeInterval::getDate('2018-02-01')->format('U') => 3,
            DateTimeInterval::getDate('2018-05-01')->format('U') => 3.6,
            DateTimeInterval::getDate('2018-08-01')->format('U') => 4,
        ];
    }
}