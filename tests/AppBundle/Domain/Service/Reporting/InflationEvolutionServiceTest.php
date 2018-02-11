<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/11/2018
 * Time: 10:54 PM
 */

namespace Tests\AppBundle\Domain\Service\Reporting;


use AppBundle\Domain\Model\Trading\Evolution;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use Tests\AppBundle\TestCase;

class InflationEvolutionServiceTest extends TestCase
{
    public function testGetEvolutionReturnsTheInflatingRatioDependingOnTheInterval()
    {
        $this->inflationService->shouldReceive('loadInflating')->andReturn([
            DateTimeInterval::getDate('today - 10 days')->format('U') => 2,
            DateTimeInterval::getDate('today + 50 days')->format('U') => 3,
            DateTimeInterval::getDate('today + 100 days')->format('U') => 3.6
        ]);

        $fromDate = DateTimeInterval::getDate('today');
        $toDate = DateTimeInterval::getDate('today + 121 days');
        $interval = new \DateInterval('P20D');

        $evolutions = $this->inflationEvolutionService->getEvolution($fromDate, $toDate, $interval);

        $this->assertInternalType('array', $evolutions);
        $this->assertCount((int) ceil(121 / 20) + 1, $evolutions);
        $this->assertInstanceOf(Evolution::class, $evolutions[0]);

        for ($i = 0; $i < (int) ceil(50 / 20); $i ++) {
            $this->assertEquals(2, $evolutions[$i]->getValue());
            $this->assertEquals(DateTimeInterval::getDate('today + ' . ($i * 20) . ' days'), $evolutions[$i]->getDate());
        }

        for ($i = (int) ceil(50 / 20); $i < (int) ceil(100 / 20); $i ++) {
            $this->assertEquals(3, $evolutions[$i]->getValue());
            $this->assertEquals(DateTimeInterval::getDate('today + ' . ($i * 20) . ' days'), $evolutions[$i]->getDate());
        }

        for ($i = (int) ceil(100 / 20); $i < (int) ceil(121 / 20); $i ++) {
            $this->assertEquals(3.6, $evolutions[$i]->getValue());
            $this->assertEquals(DateTimeInterval::getDate('today + ' . ($i * 20) . ' days'), $evolutions[$i]->getDate());
        }

        $lastIndex = (int) ceil(121 / 20);
        $this->assertEquals(3.6, $evolutions[$lastIndex]->getValue());
        $this->assertEquals(DateTimeInterval::getDate('today + 121 days'), $evolutions[$lastIndex]->getDate());
    }
}