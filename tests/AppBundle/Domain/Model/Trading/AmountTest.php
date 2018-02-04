<?php

/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 8:42 PM
 */

use AppBundle\Domain\Model\Trading\Amount;
use AppBundle\Domain\Model\Util\InvalidOperationException;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use AppBundle\Domain\Model\Trading\Currency;


class AmountTest extends TestCase
{
    public function testAdding2AmountsWithDifferentCurrencyThrowsMismatchCurrencyException()
    {
        $currencyLEI = Mockery::spy(Currency::class)->makePartial()->setSymbol('LEI');
        $currencyUSD = Mockery::spy(Currency::class)->makePartial()->setSymbol('USD');

        $amountLEI = Mockery::spy(Amount::class)->makePartial()->setValue(10)->setCurrency($currencyLEI);
        $amountUSD = Mockery::spy(Amount::class)->makePartial()->setValue(15)->setCurrency($currencyUSD);

        $this->expectExceptionCode(InvalidOperationException::ERR_CURRENCY_MISMATCH);
        $amountLEI->add($amountUSD);
    }

    public function testAdding2AmountsSumValues()
    {
        $currencyLEI = Mockery::spy(Currency::class)->makePartial()->setSymbol('LEI');

        $amount1 = Mockery::spy(Amount::class)->makePartial()->setValue(10)->setCurrency($currencyLEI);
        $amount2 = Mockery::spy(Amount::class)->makePartial()->setValue(15)->setCurrency($currencyLEI);

        $this->assertEquals($amount1->getValue() + $amount2->getValue(), $amount1->add($amount2)->getValue());
    }
}