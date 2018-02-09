<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/9/2018
 * Time: 8:28 PM
 */

namespace AppBundle\Domain\Service\Reporting;


use AppBundle\Domain\Model\Trading\Evolution;
use AppBundle\Domain\Service\Trading\InflationService;
use AppBundle\Domain\Service\Trading\EvolutionService;

class InflationEvolutionService
{
    /**
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     * @param \DateInterval $interval
     * @return Evolution[]
     */
    public function getEvolution(\DateTime $fromDate, \DateTime $toDate, \DateInterval $interval)
    {
        $return = [];

        $curDate = clone $fromDate;
        while (true) {

            $inflation = InflationService::buildInflation($curDate);
            $return[] = EvolutionService::makeEvolution(clone $curDate, $inflation->getRatio());

            $curDate = $curDate->add($interval);
            if ($curDate->format('U') >= $toDate->format('U')) {
                $curDate = clone $toDate;

                $inflation = InflationService::buildInflation($curDate);
                $return[] = EvolutionService::makeEvolution(clone $curDate, $inflation->getRatio());

                break;
            }
        }

        return $return;
    }
}