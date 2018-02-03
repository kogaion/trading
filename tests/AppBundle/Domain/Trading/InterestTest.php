<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/3/2018
 * Time: 9:22 PM
 */

use AppBundle\Domain\Model\Trading\Interest;
use \AppBundle\Domain\Model\Trading\Currency;
use \AppBundle\Domain\Model\Trading\Amount;

class InterestTest extends \Symfony\Bundle\FrameworkBundle\Tests\TestCase
{
    public function testInterestInterval()
    {
        $interval = new \DateInterval('P1Y');
        $percent = 9;
//        $date = new \DateTime();
//        $daysInYear = 365 + (int) $date->format("L");
//        $currency = Mockery::spy(Currency::class)->makePartial()->setPrecision(2);
//        $amount = Mockery::spy(Amount::class)->makePartial()->setValue($daysInYear)->setCurrency($currency);

        $interest = Mockery::spy(Interest::class)->makePartial()->setPercent($percent)->setInterval($interval);

        $interest->setInterval(new \DateInterval('P1D'));
        $this->assertNotEquals($percent, $interest->getPercent());

        $interest->setInterval(new \DateInterval('P1M'));
        $this->assertNotEquals($percent, $interest->getPercent());

        $interest->setInterval($interval);
        $this->assertEquals($percent, $interest->getPercent());
    }

    public function testInterestPercent()
    {
        $interval = new \DateInterval('P1Y');
        $percent = 9;
        $date = new \DateTime();
        $daysInYear = 365 + (int) $date->format("L");
//        $currency = Mockery::spy(Currency::class)->makePartial()->setPrecision(2);
//        $amount = Mockery::spy(Amount::class)->makePartial()->setValue($daysInYear)->setCurrency($currency);

        $interest = Mockery::spy(Interest::class)->makePartial()->setPercent($percent)->setInterval($interval);

        $interest->setInterval(new \DateInterval('P1D'));
        $this->assertEquals($percent / $daysInYear, $interest->getPercent());

        $interest->setInterval($interval);
        $this->assertEquals($percent, $interest->getPercent());
    }

    public function testInterestAmount()
    {
        $interval = new \DateInterval('P1Y');
        $percent = 9;
        $date = new \DateTime();
        $daysInYear = 365 + (int) $date->format("L");
        $currency = Mockery::spy(Currency::class)->makePartial()->setPrecision(2);
        $amount = Mockery::spy(Amount::class)->makePartial()->setValue($daysInYear)->setCurrency($currency);

        $interest = Mockery::spy(Interest::class)->makePartial()->setPercent($percent)->setInterval($interval);
        $this->assertEquals($percent * $amount->getValue() / 100, $interest->getInterest($amount)->getValue());

        $interest->setInterval(new \DateInterval('P2D'));
        $this->assertEquals($percent * $amount->getValue() / 100 / $daysInYear * 2, $interest->getInterest($amount)->getValue());
    }
}