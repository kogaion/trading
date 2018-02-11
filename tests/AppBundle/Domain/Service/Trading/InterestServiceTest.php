<?php

namespace Tests\AppBundle\Domain\Service\Trading;

/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 12:43 PM
 */


use AppBundle\Domain\Service\Trading\CurrencyService;
use Tests\AppBundle\TestCase;


class InterestServiceTest extends TestCase
{
    public function testInterestAmountChangesWithTheInterval()
    {
        $interval = new \DateInterval('P120D');
        $percent = 9;
        $value = 15;

        $amount = $this->amountService->buildAmount($value, CurrencyService::DEFAULT_CURRENCY);
        $interest = $this->interestService->makeInterest($percent, $interval);

        $this->assertEquals(
            $percent * $value / 100,
            $this->interestService->getInterestForInterval($amount, $interest, $interval)->getValue()
        );

        $this->assertEquals(
            $percent * $value / 100 * 60 / 120,
            $this->interestService->getInterestForInterval($amount, $interest, new \DateInterval('P60D'))->getValue()
        );

        $this->assertEquals(
            $percent * $value / 100 * 5 / 120,
            $this->interestService->getInterestForInterval($amount, $interest, new \DateInterval('P5D'))->getValue()
        );

        $this->assertEquals(
            $percent * $value / 100,
            $this->interestService->getInterestForInterval($amount, $interest, new \DateInterval('P120D'))->getValue()
        );

        $this->assertEquals(
            $percent * $value / 100 * 255 / 120,
            $this->interestService->getInterestForInterval($amount, $interest, new \DateInterval('P255D'))->getValue()
        );
    }
}