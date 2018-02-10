<?php

namespace Tests\AppBundle\Domain\Service\Trading;

/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 12:43 PM
 */


use AppBundle\Domain\Model\Trading\Amount;
use AppBundle\Domain\Model\Trading\Currency;
use AppBundle\Domain\Model\Trading\Interest;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use Mockery;
use Symfony\Component\Validator\Constraints\DateTime;
use Tests\AppBundle\TestCase;


class InterestServiceTest extends TestCase
{
    public function testInterestAmountChangesWithTheInterval()
    {
        $interval = new \DateInterval('P1Y');
        $percent = 9;
        $date = DateTimeInterval::getToday();
        $daysInYear = 365 + (int) $date->format("L");

        $currency = Mockery::spy(Currency::class)->makePartial();
        $amount = Mockery::spy(Amount::class)->makePartial()->setValue($daysInYear)->setCurrency($currency);
        $interest = Mockery::spy(Interest::class)->makePartial()->setPercent($percent)->setInterval($interval);

        $this->assertEquals(
            $percent * $amount->getValue() / 100,
            $this->interestService->getInterestForInterval($amount, $interest, $interval)->getValue()
        );

        $this->assertEquals(
            $percent * $amount->getValue() / 100 / $daysInYear * 2,
            $this->interestService->getInterestForInterval($amount, $interest, new \DateInterval('P2D'))->getValue()
        );
    }
}