<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 10:28 PM
 */

namespace AppBundle\Presentation\Controller;


use AppBundle\Domain\Model\Trading\Evolution;
use AppBundle\Domain\Model\Util\InvalidArgumentException;
use AppBundle\Domain\Service\Reporting\BondsEvolutionService;
use AppBundle\Domain\Service\Reporting\InflationEvolutionService;
use AppBundle\Domain\Service\Trading\PortfolioService;
use AppBundle\Domain\Service\Trading\BondsService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BondsController extends Controller
{
    public function listAction(
        BondsService $bondsService,
        PortfolioService $portfolioService,
        BondsEvolutionService $bondsEvolution,
        InflationEvolutionService $inflatingEvolution
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

            $initialValue = $portfolio->getPrice()->getValue();
            $currency = $portfolio->getUnitPrice()->getCurrency()->getSymbol();
            $precision = $portfolio->getUnitPrice()->getCurrency()->getPrecision();

            if ($initialValue == 0) {
                continue;
            }

            $bondsEvolution->setPrincipal($bonds);
            $bondsEvolution->setPortfolio($portfolio);
            $evolutions = $bondsEvolution->getEvolution(
                $portfolio->getAcquisitionDate(),
                $dateInterval
            );

            $percentSeries = array_map(function (Evolution $evolution) use ($initialValue, $currency, $precision, &$startDate, &$endDate) {
                if (null === $startDate || $startDate->format('U') > $evolution->getDate()->format('U')) {
                    $startDate = clone $evolution->getDate();
                }
                if (null === $endDate || $endDate->format('U') < $evolution->getDate()->format('U')) {
                    $endDate = clone $evolution->getDate();
                }
                return [
                    'x' => $evolution->getDate()->format('U') * 1000,
                    'y' => round($evolution->getValue() / $initialValue * 100, $precision),
                    'amount' => round($evolution->getValue(), $precision),
                    'initial' => round($initialValue, $precision),
                    'currency' => $currency,
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
        BondsEvolutionService $bondsEvolution
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
        $initialValue = $portfolio->getPrice()->getValue();
        $precision = $portfolio->getUnitPrice()->getCurrency()->getPrecision();

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

        $percentSeries = array_map(function (Evolution $evolution) use ($initialValue, $precision) {
            return [
                'x' => $evolution->getDate()->format('U') * 1000,
                'y' => round($evolution->getValue() / $initialValue * 100, $precision),
                'z' => round($evolution->getValue(), $precision)
            ];
        }, $evolutions);

        return $this->render("bonds/view.html.twig", [
            "initialValue" => $initialValue,
            'principal' => $bonds->getSymbol(),
            "periodStart" => $dateStart->format('M d, Y'),
            "periodEnd" => $dateEnd->format('M d, Y'),
            "evolutionSeries" => $evolutionSeries,
            "percentSeries" => $percentSeries,
            "currency" => $portfolio->getUnitPrice()->getCurrency()->getSymbol(),

        ]);

    }
}