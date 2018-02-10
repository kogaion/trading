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
     * @var InflationService
     */
    private $inflationService;
    /**
     * @var EvolutionService
     */
    private $evolutionService;

    /**
     * InflationEvolutionService constructor.
     * @param InflationService $inflationService
     * @param EvolutionService $evolutionService
     */
    public function __construct(InflationService $inflationService, EvolutionService $evolutionService)
    {
        $this->inflationService = $inflationService;
        $this->evolutionService = $evolutionService;
    }

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

            $inflation = $this->inflationService->buildInflation($curDate);
            $return[] = $this->evolutionService->makeEvolution(clone $curDate, $inflation->getRatio());

            $curDate = $curDate->add($interval);
            if ($curDate->format('U') >= $toDate->format('U')) {
                $curDate = clone $toDate;

                $inflation = $this->inflationService->buildInflation($curDate);
                $return[] = $this->evolutionService->makeEvolution(clone $curDate, $inflation->getRatio());

                break;
            }
        }

        return $return;
    }
}