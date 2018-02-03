<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/3/2018
 * Time: 9:22 PM
 */

use AppBundle\Domain\Model\Trading\Interest;

class InterestTest extends \Symfony\Bundle\FrameworkBundle\Tests\TestCase
{
    public function testDailyInterestPrecision()
    {
        $interval = new \DateInterval('P1Y');
        $date = new \DateTime();
        $percent = 9;

        $interest = Mockery::spy(Interest::class, [$percent, $interval])->makePartial();

        $this->assertNotEquals(round($percent  / (365 + (int) $date->format("L")), 2), $interest->getDailyInterest(3));
        $this->assertEquals(round($percent  / (365 + (int) $date->format("L")), 3), $interest->getDailyInterest(3));
        $this->assertNotEquals(round($percent  / (365 + (int) $date->format("L")), 4), $interest->getDailyInterest(3));

        $this->assertNotEquals(round($percent  / (364 + (int) $date->format("L")), 7), $interest->getDailyInterest(7));
        $this->assertEquals(round($percent  / (365 + (int) $date->format("L")), 7), $interest->getDailyInterest(7));
        $this->assertNotEquals(round($percent  / (366 + (int) $date->format("L")), 7), $interest->getDailyInterest(7));
    }


    public function testDailyInterestForAnYear()
    {
        $interval = new \DateInterval('P1Y');
        $date = new \DateTime();
        $percent = 9;

        $interest = Mockery::spy(Interest::class, [$percent, $interval])->makePartial();
        $this->assertEquals(round($percent  / (365 + (int) $date->format("L")), 3), $interest->getDailyInterest(3));
    }

    public function testDailyInterestForAMonth()
    {
        $interval = new \DateInterval('P1M');
        $date = new \DateTime();

        $interest = Mockery::spy(Interest::class, [$date->format("t"), $interval])->makePartial();
        $this->assertEquals(1.00, $interest->getDailyInterest());
    }

    public function testDailyInterestForTwoDays()
    {
        $interval = new \DateInterval('P2D');

        $interest = Mockery::spy(Interest::class, [1, $interval])->makePartial();
        $this->assertEquals(0.50, $interest->getDailyInterest());
    }
}