<?php

/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 11:37 AM
 */

use AppBundle\Domain\Model\Trading\Currency;

class CurrencyTest extends \Symfony\Bundle\FrameworkBundle\Tests\TestCase
{
    public function testCurrencySymbolIsUpperCase()
    {
        $currency = Mockery::spy(Currency::class)->makePartial();

        $currency->setSymbol('lei');

        $this->assertEquals('LEI', $currency->getSymbol());
        $this->assertNotEquals('lei', $currency->getSymbol());
    }

    public function testCurrencySymbolAcceptsThreeLettersOnly()
    {
        $currency = Mockery::spy(Currency::class)->makePartial();
        $currency->setSymbol('USD');

        $this->assertEquals('USD', $currency->getSymbol());

        $currency->setSymbol('abcd');
        $this->assertEquals('USD', $currency->getSymbol());
    }

    public function testCurrencyPrecisionIsInteger()
    {
        $currency = Mockery::spy(Currency::class)->makePartial();

        $currency->setPrecision(2);
        $this->assertEquals(2, $currency->getPrecision());

        $currency->setPrecision(3.14);
        $this->assertEquals(3, $currency->getPrecision());
    }
}