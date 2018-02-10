<?php

namespace Tests\AppBundle\Domain\Model\Trading;

/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 8:42 PM
 */

use AppBundle\Domain\Model\Trading\Amount;
use AppBundle\Domain\Model\Util\InvalidOperationException;
use AppBundle\Domain\Model\Trading\Currency;
use Mockery;
use Tests\AppBundle\TestCase;


class AmountTest extends TestCase
{
    public function testAdding2AmountsWithDifferentCurrencyThrowsMismatchCurrencyException()
    {
        $currencyLEI = Mockery::spy(Currency::class)->makePartial()->setSymbol('LEI');
        $currencyUSD = Mockery::spy(Currency::class)->makePartial()->setSymbol('USD');

        $amountLEI = (new Amount())->setValue(10)->setCurrency($currencyLEI);
        $amountUSD = (new Amount())->setValue(15)->setCurrency($currencyUSD);

        $this->expectExceptionCode(InvalidOperationException::ERR_CURRENCY_MISMATCH);
        $amountLEI->add($amountUSD);
    }

    public function testAdding2AmountsSumValues()
    {
        $currencyLEI = Mockery::spy(Currency::class)->makePartial()->setSymbol('LEI');

        $amount1 = (new Amount())->setValue(10)->setCurrency($currencyLEI);
        $amount2 = (new Amount())->setValue(15)->setCurrency($currencyLEI);

        $this->assertEquals($amount1->getValue() + $amount2->getValue(), $amount1->add($amount2)->getValue());
    }

    public function testSubstracting2AmountsWithDifferentCurrencyThrowsMismatchCurrencyException()
    {
        $currencyLEI = Mockery::spy(Currency::class)->makePartial()->setSymbol('LEI');
        $currencyUSD = Mockery::spy(Currency::class)->makePartial()->setSymbol('USD');

        $amountLEI = (new Amount())->setValue(10)->setCurrency($currencyLEI);
        $amountUSD = (new Amount())->setValue(15)->setCurrency($currencyUSD);

        $this->expectExceptionCode(InvalidOperationException::ERR_CURRENCY_MISMATCH);
        $amountLEI->sub($amountUSD);
    }

    public function testSubstracting2AmountsSubstractValues()
    {
        $currencyLEI = Mockery::spy(Currency::class)->makePartial()->setSymbol('LEI');

        $amount1 = (new Amount())->setValue(10)->setCurrency($currencyLEI);
        $amount2 = (new Amount())->setValue(15)->setCurrency($currencyLEI);

        $this->assertEquals($amount1->getValue() - $amount2->getValue(), $amount1->sub($amount2)->getValue());
    }
}