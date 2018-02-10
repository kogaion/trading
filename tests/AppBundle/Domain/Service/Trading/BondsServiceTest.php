<?php

namespace Tests\AppBundle\Domain\Service\Trading;

use AppBundle\Domain\Model\Trading\PrincipalBonds;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use AppBundle\Domain\Model\Util\InvalidArgumentException;
use AppBundle\Domain\Service\Trading\CurrencyService;
use Mockery;
use Tests\AppBundle\TestCase;

/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/10/2018
 * Time: 1:12 PM
 */

class BondsServiceTest extends TestCase
{
    public function testBuilderReturnsBonds()
    {
        $bondsSymbol = 'ABC';
        $interestPercent = 9.5;
        $interestInterval = 'P1D';
        $faceValue = 10;
        $currency = CurrencyService::DEFAULT_CURRENCY;
        $maturityDate = 'today + 1 year';
        $bonds = [$bondsSymbol => [$bondsSymbol, $interestPercent, $interestInterval, $faceValue, $currency, $maturityDate]];

        $this->bondsService->shouldReceive('loadBonds')->andReturn($bonds);

        $instance = $this->bondsService->buildBonds($bondsSymbol);

        $this->assertInstanceOf(PrincipalBonds::class, $instance);
        $this->assertEquals($bondsSymbol, $instance->getSymbol());
        $this->assertEquals($interestPercent, $instance->getInterest()->getPercent());
        $this->assertEquals(DateTimeInterval::recalculate(new \DateInterval($interestInterval)), DateTimeInterval::recalculate($instance->getInterest()->getInterval()));
        $this->assertEquals($faceValue, $instance->getFaceValue()->getValue());
        $this->assertEquals($currency, $instance->getFaceValue()->getCurrency()->getSymbol());
        $this->assertEquals(DateTimeInterval::getDate($maturityDate), $instance->getMaturityDate());
    }

    public function testListBondsReturnsArrayOfBondsIndexedBySymbol()
    {
        $bondsSymbol = 'ABC';
        $interestPercent = 9.5;
        $interestInterval = 'P1D';
        $faceValue = 10;
        $currency = CurrencyService::DEFAULT_CURRENCY;
        $maturityDate = 'today + 1 year';
        $bonds = [$bondsSymbol => [$bondsSymbol, $interestPercent, $interestInterval, $faceValue, $currency, $maturityDate]];

        $this->bondsService->shouldReceive('loadBonds')->andReturn($bonds);

        $principals = $this->bondsService->listBonds();

        $this->assertCount(count($bonds), $principals);
        $this->assertInstanceOf(PrincipalBonds::class, $principals[$bondsSymbol]);
        $this->assertArrayHasKey($bondsSymbol, $principals);

    }

    public function testBuilderThrowsExceptionForInvalidBondsSymbol()
    {
        $bondsSymbol = 'ABC';
        $interestPercent = 9.5;
        $interestInterval = 'P1D';
        $faceValue = 10;
        $currency = CurrencyService::DEFAULT_CURRENCY;
        $maturityDate = 'today + 1 year';
        $bonds = [$bondsSymbol => [$bondsSymbol, $interestPercent, $interestInterval, $faceValue, $currency, $maturityDate]];

        $this->bondsService->shouldReceive('loadBonds')->andReturn($bonds);

        $this->expectExceptionCode(InvalidArgumentException::ERR_PRINCIPAL_INVALID);
        $this->bondsService->buildBonds('DEF');
    }

    public function testBuilderCallsLoadBonds()
    {
        $this->expectExceptionCode(InvalidArgumentException::ERR_PRINCIPAL_INVALID);
        $this->bondsService->buildBonds('Something inexistent');
    }
}