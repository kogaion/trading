<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/11/2018
 * Time: 8:41 PM
 */

namespace Tests\AppBundle\Domain\Service\Reporting;


use AppBundle\Domain\Model\Trading\Evolution;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use AppBundle\Domain\Service\Trading\CurrencyService;
use Tests\AppBundle\TestCase;

class BondsEvolutionServiceTest extends TestCase
{
    public function testGetEvolutionReturnsTheAcquisitionAmountMinusPrincipalPortfolioAmountPlusInterestForPortfolioAmount()
    {
        $currency = $this->currencyService->makeCurrency(CurrencyService::DEFAULT_CURRENCY, 2);

        $bondsSymbol = 'ABC';
        $bondsInterest = $this->interestService->makeInterest(15, new \DateInterval('P7D'));
        $bondsFaceValue = $this->amountService->makeAmount(101.1, $currency);
        $bondsMaturityDate = DateTimeInterval::getDate('today + 148 days');
        $principal = $this->bondsService->makeBonds($bondsSymbol, $bondsInterest, $bondsFaceValue, $bondsMaturityDate);

        $portfolioUnits = 75;
        $portfolioUnitPrice = 105.5;
        $portfolioAcquisitionDate = DateTimeInterval::getDate('today');
        $portfolio = $this->portfolioService->makePortfolio($portfolioUnits, $this->amountService->makeAmount($portfolioUnitPrice, $currency), $portfolioAcquisitionDate);

        $this->bondsEvolutionService->setPrincipal($principal);
        $this->bondsEvolutionService->setPortfolio($portfolio);

        $fromDate = DateTimeInterval::getDate('today');
        $evolutionInterval = new \DateInterval('P2D');
        $evolutions = $this->bondsEvolutionService->getEvolution($fromDate, $evolutionInterval);

        $this->assertInternalType('array', $evolutions);
        $this->assertInstanceOf(Evolution::class, $evolutions[0]);
        $this->assertCount((int) ceil(148 / 2) + 1, $evolutions);

        for ($i = 0, $cnt = count($evolutions); $i < $cnt; $i ++) {
            $this->assertEquals(75 * 101.1 - 75 * 105.5 + 75 * 101.1 * 15 / 100 * $i * (2 / 7), $evolutions[$i]->getValue(), 'Iteration: ' . $i);
            $this->assertEquals(DateTimeInterval::getDate("today + " . ($i * 2) . " days"), $evolutions[$i]->getDate(), 'Iteration: ' . $i);
        }
    }

    public function testGetEvolutionStopsAtPrincipalMaturityDate()
    {
        $currency = $this->currencyService->makeCurrency(CurrencyService::DEFAULT_CURRENCY, 2);

        $bondsSymbol = 'ABC';
        $bondsInterest = $this->interestService->makeInterest(15, new \DateInterval('P7D'));
        $bondsFaceValue = $this->amountService->makeAmount(101.1, $currency);
        $bondsMaturityDate = DateTimeInterval::getDate('today + 249 days');
        $principal = $this->bondsService->makeBonds($bondsSymbol, $bondsInterest, $bondsFaceValue, $bondsMaturityDate);

        $portfolioUnits = 75;
        $portfolioUnitPrice = 105.5;
        $portfolioAcquisitionDate = DateTimeInterval::getDate('today');
        $portfolio = $this->portfolioService->makePortfolio($portfolioUnits, $this->amountService->makeAmount($portfolioUnitPrice, $currency), $portfolioAcquisitionDate);

        $this->bondsEvolutionService->setPrincipal($principal);
        $this->bondsEvolutionService->setPortfolio($portfolio);

        $fromDate = DateTimeInterval::getDate('today');
        $evolutionInterval = new \DateInterval('P2D');
        $evolutions = $this->bondsEvolutionService->getEvolution($fromDate, $evolutionInterval);

        $lastIndex = (int) ceil(249 / 2);

        $this->assertInternalType('array', $evolutions);
        $this->assertInstanceOf(Evolution::class, $evolutions[0]);
        $this->assertCount($lastIndex + 1, $evolutions);
        $this->assertEquals(DateTimeInterval::getDate("today + 249 days"), $evolutions[$lastIndex]->getDate());
        $this->assertEquals(75 * 101.1 - 75 * 105.5 + 75 * 101.1 * 15 / 100 * (($lastIndex - 1) * (2 / 7) + ((249 % 2) / 7)), $evolutions[$lastIndex]->getValue());


    }
}