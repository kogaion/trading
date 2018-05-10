<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 10:28 PM
 */

namespace AppBundle\Presentation\Controller;


use AppBundle\Domain\Model\Trading\Currency;
use AppBundle\Domain\Model\Trading\Evolution;
use AppBundle\Domain\Model\Trading\Portfolio;
use AppBundle\Domain\Model\Trading\Bond;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use AppBundle\Domain\Model\Util\InvalidArgumentException;
use AppBundle\Domain\Service\Reporting\BondsEvolutionService;
use AppBundle\Domain\Service\Reporting\InflationEvolutionService;
use AppBundle\Domain\Service\Trading\CurrencyService;
use AppBundle\Domain\Service\Trading\InflationService;
use AppBundle\Domain\Service\Trading\PortfolioService;
use AppBundle\Domain\Service\Trading\BondsService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BondsController extends Controller
{
    /**
     * @param Request $request
     * @param BondsService $bondsService
     * @param PortfolioService $portfolioService
     * @param BondsEvolutionService $bondsEvolution
     * @param InflationEvolutionService $inflatingEvolution
     * @param CurrencyService $currencyService
     * @param InflationService $inflationService
     * @return Response
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    public function listAction(
        Request $request,
        BondsService $bondsService,
        PortfolioService $portfolioService,
        BondsEvolutionService $bondsEvolution,
        InflationEvolutionService $inflatingEvolution,
        CurrencyService $currencyService,
        InflationService $inflationService
    )
    {
        $virtualPortfolioStartDate = $request->query->get('sd') ?: 'today';
        
        // increment interval, default currency
        $dateInterval = new \DateInterval('P10D');
        $currency = $currencyService->buildCurrency(CurrencyService::DEFAULT_CURRENCY); // @todo how else?
        
        $bondsSeries = [];
        $allBonds = $bondsService->listBonds();
        $portfolios = $portfolioService->listPortfolios();
        
        foreach ($portfolios as $keyP => $p) {
            foreach ($allBonds as $b) {
                if ($b->getSymbol() == $p->getSymbol()) {
                    $b = $bondsService->buildBonds($p->getSymbol());
                    $bondsSeries[] = [$b, $p, true];
                }
            }
        }
        
        foreach ($allBonds as $b) {
            $p = $portfolioService->buildVirtualPortfolio($b->getSymbol(), null, $virtualPortfolioStartDate);
            $bondsSeries[] = [$b, $p, false];
        }
        
        list($series, $startDate, $endDate) = $this->getBondsSeries(
            $request,
            $bondsEvolution,
            $inflationService,
            $inflatingEvolution,
            $bondsSeries,
            $dateInterval,
            $currency
        );
        
        return $this->render("bonds/list.html.twig", [
            "series" => $series,
            'title' => 'Bonds profit evolution',
            'subtitle' => 'Period: ' . $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y'),
            'xLabel' => 'Date',
            'yLabel' => 'Profit ratio (%)',
        ]);
    }
    
    /**
     * @param $bondsSymbol
     * @param Request $request
     * @param BondsService $bondsService
     * @param PortfolioService $portfolioService
     * @param BondsEvolutionService $bondsEvolution
     * @param InflationEvolutionService $inflatingEvolution
     * @param CurrencyService $currencyService
     * @param InflationService $inflationService
     * @return Response
     * @throws \Exception
     */
    public function viewAction(
        $bondsSymbol,
        Request $request,
        BondsService $bondsService,
        PortfolioService $portfolioService,
        BondsEvolutionService $bondsEvolution,
        InflationEvolutionService $inflatingEvolution,
        CurrencyService $currencyService,
        InflationService $inflationService
    )
    {
        $simulatePrice = $request->query->get('simp');
        $simulateDate = $request->query->get('simd');
        $virtualPortfolioStartDate = $request->query->get('sd') ?: 'today';
        
        try {
            $bonds = $bondsService->buildBonds($bondsSymbol);
            
            $bondsSeries = [];
            $portfolio = $portfolioService->buildPortfolio($bondsSymbol);
            if ($portfolio) {
                foreach ($portfolio as $p) {
                    $bondsSeries[] = [$bonds, $p, true];
                }
            }
            
            $bondsSeries[] = [$bonds, $portfolioService->buildVirtualPortfolio($bondsSymbol, null, $virtualPortfolioStartDate), false];
            
            if ($simulatePrice) {
                foreach ($simulatePrice as $key => $simulatedPrice) {
                    $simulatedDate = !empty($simulateDate[$key]) ? $simulateDate[$key] : $virtualPortfolioStartDate;
                    $bondsSeries[] = [$bonds, $portfolioService->buildVirtualPortfolio($bondsSymbol, $simulatedPrice, $simulatedDate), false];
                }
            }
            
            // increment interval, default currency
            $dateInterval = new \DateInterval('P10D');
            $currency = $currencyService->buildCurrency(CurrencyService::DEFAULT_CURRENCY); // @todo how else?
            
            list($series, $startDate, $endDate) = $this->getBondsSeries(
                $request,
                $bondsEvolution,
                $inflationService,
                $inflatingEvolution,
                $bondsSeries,
                $dateInterval,
                $currency
            );
            
            return $this->render("bonds/list.html.twig", [
                "series" => $series,
                'title' => 'Bonds profit evolution for: ' . $bondsSymbol,
                'subtitle' => 'Period: ' . $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y'),
                'xLabel' => 'Date',
                'yLabel' => 'Profit ratio (%)',
            ]);
            
            
        } catch (InvalidArgumentException $e) {
            throw $this->createNotFoundException("The bonds '{$bondsSymbol}' does not exist");
        }
        
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
    
    private function getSeries($initialValue, $evolutions, Currency $currency = null)
    {
        $minDate = $maxDate = null;
        $percentSeries = array_map(function (Evolution $evolution) use ($initialValue, $currency, &$minDate, &$maxDate) {
            
            $date = $evolution->getDate();
            $value = $evolution->getValue();
            
            $minDate = clone ($minDate ? min($minDate, $date) : $date);
            $maxDate = clone ($maxDate ? max($maxDate, $date) : $date);
            
            $precision = $currency ? $currency->getPrecision() : 2;
            
            return [
                'x' => $date->format('U') * 1000,
                'y' => round($value / $initialValue * 100, $precision),
                'initial' => round($initialValue, $precision),
                'currency' => $currency ? $currency->getSymbol() : '',
                'amount' => round($value, $precision),
            ];
        }, $evolutions);
        
        return [
            'series' => $percentSeries,
            'minDate' => $minDate,
            'maxDate' => $maxDate,
        ];
    }
    
    /**
     * @param Request $request
     * @param BondsEvolutionService $bondsEvolution
     * @param InflationService $inflationService
     * @param InflationEvolutionService $inflatingEvolution
     * @param $bondsSeries
     * @param $dateInterval
     * @param $currency
     * @return array
     */
    protected function getBondsSeries(
        Request $request,
        BondsEvolutionService $bondsEvolution,
        InflationService $inflationService,
        InflationEvolutionService $inflatingEvolution,
        $bondsSeries,
        $dateInterval,
        $currency
    )
    {
        $getOverInflation = $request->query->get('oi') ?: false;
        $getPositivePortfolios = $request->query->get('pp') ?: false;
        
        $startDate = $endDate = null;
        $series = [];
        $names = [];
        foreach ($bondsSeries as $data) {
            
            /**
             * @var Bond $bonds
             * @var Portfolio $portfolio
             */
            $bonds = $data[0];
            $portfolio = $data[1];
            $sticky = $data[2];
            
            $portfolioValue = $portfolio->getPrice();
            if ($portfolioValue == 0) {
                continue;
            }
            
            $bondsEvolution->setPrincipal($bonds);
            $bondsEvolution->setPortfolio($portfolio);
            $evolutions = $bondsEvolution->getEvolution($dateInterval);
            
            $lastIndex = count($evolutions) - 1;
            
            // skip negative evolutions
            if ($getPositivePortfolios
                && !$sticky
                && ($evolutions[$lastIndex]->getValue() < 0)
            ) {
                continue;
            }
            
            // skip portfolios that do not make it over the inflation
            if ($getOverInflation
                && !$sticky
                && (($evolutions[$lastIndex]->getValue() / $portfolioValue * 100) < $inflationService->buildInflation($evolutions[$lastIndex]->getDate())->getRatio())
            ) {
                continue;
            }
            
            $symbol = $bonds->getSymbol();
            $names[$symbol] = (isset($names[$symbol]) ? ++$names[$symbol] : 1);
            $seriesData = $this->getSeries($portfolioValue, $evolutions, $currency);
            $series[] = [
                "name" => $symbol . ($sticky ? '(!)' : '') . ($names[$symbol] > 1 ? ' #' . $names[$symbol] : ''),
                "data" => $seriesData['series'],
            ];
            
            
            $startDate = $startDate ? min($seriesData['minDate'], $startDate) : $seriesData['minDate'];
            $endDate = $endDate ? max($seriesData['maxDate'], $endDate) : $seriesData['maxDate'];
        }
        
        // add inflation series
        $inflationEvolutions = $inflatingEvolution->getEvolution($startDate, $endDate, $dateInterval);
        $seriesData = $this->getSeries(100, $inflationEvolutions, null);
        $series[] = [
            "name" => 'Inflation',
            "data" => $seriesData['series'],
        ];
        
        return array($series, $startDate, $endDate);
    }
    
    
}