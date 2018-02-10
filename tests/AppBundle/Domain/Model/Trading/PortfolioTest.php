<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/10/2018
 * Time: 6:07 PM
 */

namespace Tests\AppBundle\Domain\Model\Trading;


use AppBundle\Domain\Model\Trading\Amount;
use AppBundle\Domain\Model\Trading\Currency;
use AppBundle\Domain\Model\Trading\Portfolio;
use Mockery;
use Tests\AppBundle\TestCase;

class PortfolioTest extends TestCase
{
    public function testPriceCurrencyFollowsUnitPriceCurrency()
    {
        $currency = Mockery::mock(Currency::class)->makePartial()->setSymbol('xyz');
        $unitPrice = Mockery::spy(Amount::class)->makePartial()->setValue(10)->setCurrency($currency);

        $portfolio = (new Portfolio())->setUnitPrice($unitPrice);

        $this->assertEquals($currency, $portfolio->getPrice()->getCurrency());
    }

    public function testPriceIsUnitPriceMultipliedByBalance()
    {
        $currency = Mockery::mock(Currency::class)->makePartial()->setSymbol('xyz');
        $unitPrice = Mockery::spy(Amount::class)->makePartial()->setValue(10)->setCurrency($currency);

        $portfolio = (new Portfolio())->setUnitPrice($unitPrice)->setBalance(100);
        $this->assertEquals(100 * 10, $portfolio->getPrice()->getValue());

        $portfolio->setBalance(740);
        $this->assertEquals(740 * 10, $portfolio->getPrice()->getValue());

        $unitPrice->setValue(16);
        $portfolio->setUnitPrice($unitPrice);
        $this->assertEquals(740 * 16, $portfolio->getPrice()->getValue());
    }
}