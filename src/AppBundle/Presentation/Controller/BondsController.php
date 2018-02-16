<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 10:28 PM
 */

namespace AppBundle\Presentation\Controller;


use AppBundle\Domain\Model\Trading\Evolution;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use AppBundle\Domain\Model\Util\InvalidArgumentException;
use AppBundle\Domain\Service\Reporting\BondsEvolutionService;
use AppBundle\Domain\Service\Reporting\InflationEvolutionService;
use AppBundle\Domain\Service\Trading\CurrencyService;
use AppBundle\Domain\Service\Trading\PortfolioService;
use AppBundle\Domain\Service\Trading\BondsService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BondsController extends Controller
{
    public function listAction(
        BondsService $bondsService,
        PortfolioService $portfolioService,
        BondsEvolutionService $bondsEvolution,
        InflationEvolutionService $inflatingEvolution,
        CurrencyService $currencyService
    )
    {
        $allBonds = $bondsService->listBonds();
        
        $series = [];
        /**
         * @var \DateTime $startDate
         * @var \DateTime $endDate
         */
        $startDate = $endDate = null;
        
        $dateInterval = new \DateInterval('P10D');
        
        foreach ($allBonds as $bondsSymbol => $bonds) {
            try {
                $portfolio = $portfolioService->buildPortfolio($bondsSymbol);
            } catch (InvalidArgumentException $e) {
                throw $this->createNotFoundException("The bonds '{$bondsSymbol}' does not exist");
            }
            
            $initialValue = $portfolio->getPrice();
            $currency = $currencyService->buildCurrency(CurrencyService::DEFAULT_CURRENCY); // @todo actually this might differ
            
            if ($initialValue == 0) {
                continue;
            }
            
            $bondsEvolution->setPrincipal($bonds);
            $bondsEvolution->setPortfolio($portfolio);
            $evolutions = $bondsEvolution->getEvolution(
                $portfolio->getAcquisitionDate(),
                $dateInterval
            );
    
            // remove negative evolutions
            if ($evolutions[count($evolutions) - 1]->getValue() < 0) {
//                continue;
            }
            
            $percentSeries = array_map(function (Evolution $evolution) use ($initialValue, $currency, &$startDate, &$endDate) {
                if (null === $startDate || $startDate->format('U') > $evolution->getDate()->format('U')) {
                    $startDate = clone $evolution->getDate();
                }
                if (null === $endDate || $endDate->format('U') < $evolution->getDate()->format('U')) {
                    $endDate = clone $evolution->getDate();
                }
                return [
                    'x' => $evolution->getDate()->format('U') * 1000,
                    'y' => round($evolution->getValue() / $initialValue * 100, $currency->getPrecision()),
                    'amount' => round($evolution->getValue(), $currency->getPrecision()),
                    'initial' => round($initialValue, $currency->getPrecision()),
                    'currency' => $currency->getSymbol(),
                ];
            }, $evolutions);
            
            $series[] = [
                "name" => $bonds->getSymbol(),
                "data" => $percentSeries,
            ];
        }
        
        // add inflation
        $inflationEvolutions = $inflatingEvolution->getEvolution($startDate, $endDate, $dateInterval);
        $inflationSeries = array_map(function (Evolution $evolution) {
            return [
                'x' => $evolution->getDate()->format('U') * 1000,
                'y' => $evolution->getValue(),
            ];
        }, $inflationEvolutions);
        $series[] = [
            "name" => 'Inflation',
            "data" => $inflationSeries,
        ];
        
        return $this->render("bonds/list.html.twig", [
            "series" => $series,
            "startDate" => $startDate->format('M d, Y'),
            "endDate" => $endDate->format('M d, Y'),
        ]);
    }
    
    public function viewAction(
        $bondsSymbol,
        BondsService $bondsService,
        PortfolioService $portfolioService,
        BondsEvolutionService $bondsEvolution,
        CurrencyService $currencyService
    )
    {
        try {
            $bonds = $bondsService->buildBonds($bondsSymbol);
            $portfolio = $portfolioService->buildPortfolio($bondsSymbol);
        } catch (InvalidArgumentException $e) {
            throw $this->createNotFoundException("The bonds '{$bondsSymbol}' does not exist");
        }
        
        $bondsEvolution->setPrincipal($bonds);
        $bondsEvolution->setPortfolio($portfolio);
        $evolutions = $bondsEvolution->getEvolution(
            $portfolio->getAcquisitionDate(),
            new \DateInterval('P7D')
        );
        
        /**
         * @var \DateTime $dateStart
         * @var \DateTime $dateEnd
         */
        $dateStart = $dateEnd = null;
        $initialValue = $portfolio->getPrice();
        $currency = $currencyService->buildCurrency(CurrencyService::DEFAULT_CURRENCY);
        
        $evolutionSeries = array_map(function (Evolution $evolution) use (&$dateStart, &$dateEnd) {
            if (null === $dateStart) {
                $dateStart = $evolution->getDate();
            }
            $dateEnd = $evolution->getDate();
            return [
                $evolution->getDate()->format('U') * 1000,
                $evolution->getValue()
            ];
        }, $evolutions);
        
        $percentSeries = array_map(function (Evolution $evolution) use ($initialValue, $currency) {
            return [
                'x' => $evolution->getDate()->format('U') * 1000,
                'y' => round($evolution->getValue() / $initialValue * 100, $currency->getPrecision()),
                'z' => round($evolution->getValue(), $currency->getPrecision())
            ];
        }, $evolutions);
        
        return $this->render("bonds/view.html.twig", [
            "initialValue" => $initialValue,
            'principal' => $bonds->getSymbol(),
            "periodStart" => $dateStart->format('M d, Y'),
            "periodEnd" => $dateEnd->format('M d, Y'),
            "evolutionSeries" => $evolutionSeries,
            "percentSeries" => $percentSeries,
            "currency" => $currency->getSymbol(),

        ]);
        
    }
    
    public function calculusAction()
    {
        $previousCouponDate = '2017-07-31';
        $acquisitionDate = 'today';
        $couponPercent = 0.08;
        $couponFrequency = 1 / 6; // tri-month coupon // assume at least monthly coupons

//        FFS date add
        
        $faceValue = 1000;
        $maturityDate = '2018-11-27';
//        $n = 4; // how many coupons left
        $yield = 0.0865; // yearly


//        $previousCouponDate = '1997-03-01';
//        $nextCouponDate = '1997-09-01';
//        $acquisitionDate = '1997-07-17';
//        $couponPercent = 0.1;
//        $couponFrequency = 1 / 2;
//        $faceValue = 100;
//        $maturityDate = '2018-11-27';
//        $n = 12; // how many coupons left
//        $yield = 0
        
        
        $yield = $yield * $couponFrequency;
        
        $coupon = $couponFrequency * $couponPercent * $faceValue;
        
        $previousCouponDate = DateTimeInterval::getDate($previousCouponDate);
        $nextCouponDate = clone $previousCouponDate;
        $nextCouponDate = $nextCouponDate->add(new \DateInterval('P' . (12 * $couponFrequency) . 'M'));
        
        var_dump($nextCouponDate);
        $acquisitionDate = DateTimeInterval::getDate($acquisitionDate);
        $maturityDate = DateTimeInterval::getDate($maturityDate);
        $nrDaysLeft = $acquisitionDate->diff($nextCouponDate);
        $nrDaysLeft = $nrDaysLeft->days;
        $nrDaysPassed = $previousCouponDate->diff($acquisitionDate);
        $nrDaysPassed = $nrDaysPassed->days;
        $nrDaysCurrentCoupon = $previousCouponDate->diff($nextCouponDate);
        $nrDaysCurrentCoupon = $nrDaysCurrentCoupon->days;
        
        // how many coupons left
        $n = $maturityDate->diff($previousCouponDate);
        $n = ($n->y * 12 + $n->m) / (12 * $couponFrequency);
        
        $w = $nrDaysLeft / $nrDaysCurrentCoupon;
        
        
        $grossPrice = (
            $coupon / pow(1 + $yield, $w)
            *
            (pow(1 + $yield, $n) - 1)
            /
            (pow(1 + $yield, $n - 1) * $yield)
            +
            $faceValue / pow(1 + $yield, $n - 1 + $w)
        );
        
        $interest = $couponPercent * $couponFrequency * $nrDaysPassed / $nrDaysCurrentCoupon * $faceValue;
        
        $netPrice = $grossPrice - $interest;
        
        return new Response(json_encode([$grossPrice, $netPrice, $interest]));
        
        
    }
}