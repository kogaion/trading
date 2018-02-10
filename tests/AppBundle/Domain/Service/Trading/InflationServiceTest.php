<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/10/2018
 * Time: 8:05 PM
 */

namespace Tests\AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\Inflation;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use AppBundle\Domain\Service\Trading\InflationService;
use Tests\AppBundle\TestCase;

class InflationServiceTest extends TestCase
{
    public function testFactoryReturnsInflation()
    {
        $ratio = 10;
        $date = DateTimeInterval::getToday();

        $instance = (new InflationService())->makeInflation($ratio, $date);
        $this->assertInstanceOf(Inflation::class, $instance);
        $this->assertEquals($ratio, $instance->getRatio());
        $this->assertEquals($date, $instance->getDate());
    }

    public function testBuilderCallsLoadInflating()
    {
        $instance = $this->inflationService->buildInflation(DateTimeInterval::getToday());
        $this->assertInstanceOf(Inflation::class, $instance);
    }

    public function testBuilderReturnsInflation()
    {
        $today = DateTimeInterval::getDate('today')->format('U');
        $tomorrow = DateTimeInterval::getDate('today + 1 year')->format('U');

        $inflating = [
            $today => 20,
            $tomorrow => 40,
        ];

        $this->inflationService->shouldReceive('loadInflating')->andReturn($inflating);

        $instance = $this->inflationService->buildInflation(DateTimeInterval::getToday());
        $this->assertInstanceOf(Inflation::class, $instance);
        $this->assertEquals($inflating[$today], $instance->getRatio());
        $this->assertEquals(DateTimeInterval::getToday(), $instance->getDate());
    }

    public function testBuilderReturnsInflationInDateInterval()
    {
        $today = DateTimeInterval::getDate('today')->format('U');
        $nextYear = DateTimeInterval::getDate('today + 1 year')->format('U');
        $inflating = [
            $today => 20,
            $nextYear => 40,
        ];

        $this->inflationService->shouldReceive('loadInflating')->andReturn($inflating);

        $instance = $this->inflationService->buildInflation(DateTimeInterval::getDate('tomorrow'));
        $this->assertEquals($inflating[$today], $instance->getRatio());

        $instance = $this->inflationService->buildInflation(DateTimeInterval::getDate('yesterday'));
        $this->assertEquals($inflating[$today], $instance->getRatio());

        $instance = $this->inflationService->buildInflation(DateTimeInterval::getDate('today + 1 year'));
        $this->assertEquals($inflating[$nextYear], $instance->getRatio());

        $instance = $this->inflationService->buildInflation(DateTimeInterval::getDate('today + 2 years'));
        $this->assertEquals($inflating[$nextYear], $instance->getRatio());
    }

    public function testListInflatingReturnsArrayOfInflation()
    {
        $today = DateTimeInterval::getDate('today')->format('U');
        $nextYear = DateTimeInterval::getDate('today + 1 year')->format('U');
        $inflating = [
            $today => 20,
            $nextYear => 40,
        ];

        $this->inflationService->shouldReceive('loadInflating')->andReturn($inflating);

        $inflatingList = $this->inflationService->listInflating();
        $this->assertCount(count($inflating), $inflatingList);
        $this->assertInstanceOf(Inflation::class, $inflatingList[0]);

    }
}