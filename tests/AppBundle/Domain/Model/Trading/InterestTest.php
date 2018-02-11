<?php

namespace Tests\AppBundle\Domain\Model\Trading;

/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/3/2018
 * Time: 9:22 PM
 */

use AppBundle\Domain\Model\Trading\Interest;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use Mockery;
use Tests\AppBundle\TestCase;

class InterestTest extends TestCase
{
    public function testInterestIsRecalculatedWithEachInterval()
    {
        $interval = new \DateInterval('P1Y');
        $percent = 9;

        $interest = Mockery::spy(Interest::class)->makePartial()->setPercent($percent)->setInterval($interval);

        $interest->setInterval(new \DateInterval('P1D'));
        $this->assertLessThan($percent, $interest->getPercent());

        $interest->setInterval(new \DateInterval('P1M'));
        $this->assertLessThan($percent, $interest->getPercent());

        $interest->setInterval(new \DateInterval('P2Y'));
        $this->assertGreaterThan($percent, $interest->getPercent());

        $interest->setInterval($interval);
        $this->assertEquals($percent, $interest->getPercent());
    }

    public function testInterestPercentFormula()
    {
        $interval = new \DateInterval('P1Y');
        $percent = 9;
        $date = DateTimeInterval::getToday();
        $daysInYear = 365 + (int)$date->format("L");

        $interest = Mockery::spy(Interest::class)->makePartial()->setPercent($percent)->setInterval($interval);

        $interest->setInterval(new \DateInterval('P1D'));
        $this->assertEquals($percent / $daysInYear, $interest->getPercent());

        $interest->setInterval($interval);
        $this->assertEquals($percent, $interest->getPercent());
    }

    public function testInterestPercentRangeIsIntegerBetween0And100()
    {
        $interest = Mockery::spy(Interest::class)->makePartial();

        $interest->setPercent(105);
        $this->assertEquals(100, $interest->getPercent());

        $interest->setPercent(-2);
        $this->assertEquals(0, $interest->getPercent());

        $interest->setPercent(12.222);
        $this->assertEquals(12.22, $interest->getPercent());
    }
}