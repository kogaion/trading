<?php

namespace Tests\AppBundle\Domain\Service\Trading;

use AppBundle\Domain\Model\Trading\Amount;
use AppBundle\Domain\Model\Trading\Currency;
use AppBundle\Domain\Service\Trading\AmountService;
use Tests\AppBundle\TestCase;

/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/10/2018
 * Time: 1:14 PM
 */
class AmountServiceTest extends TestCase
{
    public function testBuilderReturnsAmount()
    {
        $value = 10;
        $symbol = 'LEI';

        $service = new AmountService($this->currencyService);
        $instance = $service->buildAmount($value, $symbol);

        $this->assertInstanceOf(Amount::class, $instance);
        $this->assertEquals($value, $instance->getValue());
        $this->assertInstanceOf(Currency::class, $instance->getCurrency());
        $this->assertEquals($symbol, $instance->getCurrency()->getSymbol());

    }

    public function testFactoryReturnsAmount()
    {
        $value = 10;
        $symbol = 'LEI';

        $service = new AmountService($this->currencyService);

        $currency = $this->currencyService->buildCurrency($symbol);
        $instance = $service->makeAmount($value, $currency);

        $this->assertInstanceOf(Amount::class, $instance);
        $this->assertEquals($value, $instance->getValue());
        $this->assertInstanceOf(Currency::class, $instance->getCurrency());
        $this->assertEquals($currency, $instance->getCurrency());
    }
}