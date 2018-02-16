<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/10/2018
 * Time: 9:34 PM
 */

namespace Tests\AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\Portfolio;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use AppBundle\Domain\Service\Trading\CurrencyService;
use Tests\AppBundle\TestCase;

class PortfolioServiceTest extends TestCase
{
    public function testFactoryReturnsPortfolio()
    {
        $unitPrice = $this->amountService->buildAmount(20, CurrencyService::DEFAULT_CURRENCY);
        $acquisitionDate = DateTimeInterval::getDate('today');
        $balance = 100;

        $instance = $this->portfolioService->makePortfolio($balance, $unitPrice, $acquisitionDate);
        $this->assertInstanceOf(Portfolio::class, $instance);
        $this->assertEquals($balance, $instance->getBalance());
        $this->assertEquals($unitPrice, $instance->getUnitPrice());
        $this->assertEquals($acquisitionDate, $instance->getAcquisitionDate());
    }

    public function testPortfolioPriceIsUnitPriceMultipliedByBalance()
    {
        $unitPrice = $this->amountService->buildAmount(20, CurrencyService::DEFAULT_CURRENCY);
        $acquisitionDate = DateTimeInterval::getDate('today');
        $balance = 100;

        $instance = $this->portfolioService->makePortfolio($balance, $unitPrice, $acquisitionDate);
        $this->assertEquals($balance * $unitPrice->getValue(), $instance->getPrice()->getValue());
        $this->assertEquals($unitPrice->getCurrency(), $instance->getPrice()->getCurrency());

        $balance = 40;
        $instance->setBalance($balance);
        $this->assertEquals($balance * $unitPrice->getValue(), $instance->getPrice()->getValue());

        $unitPrice->setValue(150);
        $instance->setUnitPrice($unitPrice);
        $this->assertEquals($balance * $unitPrice->getValue(), $instance->getPrice()->getValue());
    }

    public function testBuilderReturnsPortfolio()
    {
        $unitPrice = 20.25;
        $currency = CurrencyService::DEFAULT_CURRENCY;
        $balance = 14;
        $acquisitionDate = 'today';
        $symbol = 'xyz';
        $portfolio = [[$symbol, $balance, $unitPrice, $currency, $acquisitionDate]];

        $this->portfolioService->shouldReceive('loadPortfolio')->andReturn($portfolio);

        $instance = $this->portfolioService->buildPortfolio($symbol);
        $this->assertInstanceOf(Portfolio::class, $instance);
        $this->assertEquals($balance, $instance->getBalance());
        $this->assertEquals($unitPrice, $instance->getUnitPrice()->getValue());
        $this->assertEquals($currency, $instance->getUnitPrice()->getCurrency()->getSymbol());
        $this->assertEquals(DateTimeInterval::getDate($acquisitionDate), $instance->getAcquisitionDate());
    }

    public function testBuilderCallsLoadPortfolio()
    {
        $instance = $this->portfolioService->buildPortfolio('Something inexistent!');
        $this->assertInstanceOf(Portfolio::class, $instance);
    }

    public function testBuilderReturnsEmptyPortfolioForUnknownSymbols()
    {
        $instance = $this->portfolioService->buildPortfolio('Something inexistent!');
        $this->assertEquals(0, $instance->getBalance());
        $this->assertEquals(0, $instance->getUnitPrice()->getValue());
        $this->assertEquals(0, $instance->getPrice()->getValue());
        $this->assertEquals(CurrencyService::DEFAULT_CURRENCY, $instance->getUnitPrice()->getCurrency()->getSymbol());
        $this->assertEquals(DateTimeInterval::getDate('today'), $instance->getAcquisitionDate());
    }

    public function testListPortfolioReturnsArrayOfPortfolioIndexedBySymbol()
    {
        $unitPrice = 20.25;
        $currency = CurrencyService::DEFAULT_CURRENCY;
        $balance = 14;
        $acquisitionDate = 'today';
        $symbol = 'xyz';
        $portfolio = [[$symbol, $balance, $unitPrice, $currency, $acquisitionDate]];

        $this->portfolioService->shouldReceive('loadPortfolio')->andReturn($portfolio);

        $portfolioList = $this->portfolioService->listPortfolio();
        $this->assertCount(count($portfolio), $portfolioList);
        $this->assertInstanceOf(Portfolio::class, $portfolioList[0]);
    }
}