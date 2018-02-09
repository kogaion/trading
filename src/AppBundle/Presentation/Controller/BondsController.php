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
use AppBundle\Domain\Service\Trading\AmountService;
use AppBundle\Domain\Service\Trading\InterestService;
use AppBundle\Domain\Service\Trading\PortfolioService;
use AppBundle\Domain\Service\Trading\BondsService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BondsController extends Controller
{

    public function listAction()
    {
        $bondsService = new BondsService();
        $portfolioService = new PortfolioService();

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

            if ($initialValue == 0) {
                continue;
            }

            $bondsEvolution = new BondsEvolutionService(new AmountService(), new InterestService());
            $bondsEvolution->setPrincipal($bonds);
            $bondsEvolution->setPortfolio($portfolio);
            $evolutions = $bondsEvolution->getEvolution(
                $portfolio->getAcquisitionDate(),
                $dateInterval
            );

            $percentSeries = array_map(function (Evolution $evolution) use ($initialValue, $currency, &$startDate, &$endDate) {
                if (null === $startDate || $startDate->format('U') > $evolution->getDate()->format('U')) {
                    $startDate = clone $evolution->getDate();
                }
                if (null === $endDate || $endDate->format('U') < $evolution->getDate()->format('U')) {
                    $endDate = clone $evolution->getDate();
                }
                return [
                    'x' => $evolution->getDate()->format('U') * 1000,
                    'y' => $evolution->getValue() / $initialValue * 100,
                    'amount' => $evolution->getValue(),
                    'initial' => $initialValue,
                    'currency' => $currency,
                ];
            }, $evolutions);

            $series[] = [
                "name" => $bonds->getSymbol(),
                "data" => $percentSeries,
            ];
        }

        // add inflation
        $inflationEvolutionService = new InflationEvolutionService();
        $inflationEvolutions = $inflationEvolutionService->getEvolution($startDate, $endDate, $dateInterval);
        $inflationSeries = array_map(function(Evolution $evolution) {
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

    public function viewAction($bondsSymbol)
    {
        $portfolioService = new PortfolioService();

        try {
            $bonds = BondsService::buildBonds($bondsSymbol);
            $portfolio = $portfolioService->buildPortfolio($bondsSymbol);
        } catch (InvalidArgumentException $e) {
            throw $this->createNotFoundException("The bonds '{$bondsSymbol}' does not exist");
        }

        $bondsEvolution = new BondsEvolutionService(new AmountService(), new InterestService());
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
        $currency = $portfolio->getUnitPrice()->getCurrency();

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
                'y' => $evolution->getValue() / $initialValue * 100,
                'z' => $evolution->getValue()
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