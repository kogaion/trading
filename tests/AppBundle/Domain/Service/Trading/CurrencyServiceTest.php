<?php

namespace Tests\AppBundle\Domain\Service\Trading;

use AppBundle\Domain\Model\Trading\Currency;
use AppBundle\Domain\Service\Trading\CurrencyService;
use AppBundle\Domain\Model\Util\InvalidArgumentException;
use Tests\AppBundle\TestCase;

/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/10/2018
 * Time: 1:33 PM
 */
class CurrencyServiceTest extends TestCase
{
    public function testBuilderReturnsCurrency()
    {
        $symbol = 'XYZ';
        $this->currencyService->shouldReceive('loadCurrencies')->andReturn([$symbol => 2]);

        $instance = $this->currencyService->buildCurrency($symbol);

        $this->assertInstanceOf(Currency::class, $instance);
        $this->assertEquals($symbol, $instance->getSymbol());
    }

    public function testBuilderRecognizeOnlyValidCurrencies()
    {
        $symbol = 'XYZ';
        $this->currencyService->shouldReceive('loadCurrencies')->andReturn([CurrencyService::DEFAULT_CURRENCY => 2]);

        $this->expectExceptionCode(InvalidArgumentException::ERR_CURRENCY_INVALID);
        $this->currencyService->buildCurrency($symbol);
    }

    public function testBuilderCallsLoadCurrencies()
    {
        $this->expectExceptionCode(InvalidArgumentException::ERR_CURRENCY_INVALID);
        $this->currencyService->buildCurrency('Something inexistent');
    }
}