<?php

namespace Tests\AppBundle\Domain\Model\Trading;

/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 11:37 AM
 */

use AppBundle\Domain\Model\Trading\Currency;
use Tests\AppBundle\TestCase;

class CurrencyTest extends TestCase
{
    public function testCurrencySymbolIsUpperCase()
    {
        $currency = new Currency;
        $currency->setSymbol('lei');

        $this->assertEquals('LEI', $currency->getSymbol());
        $this->assertNotEquals('lei', $currency->getSymbol());
    }

    public function testCurrencyPrecisionIsInteger()
    {
        $currency = new Currency;

        $currency->setPrecision(2);
        $this->assertEquals(2, $currency->getPrecision());

        $currency->setPrecision(3.14);
        $this->assertEquals(3, $currency->getPrecision());
    }
}