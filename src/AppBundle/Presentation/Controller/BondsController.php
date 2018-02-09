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
use AppBundle\Domain\Service\Trading\AmountService;
use AppBundle\Domain\Service\Trading\InterestService;
use AppBundle\Domain\Service\Trading\PortfolioService;
use AppBundle\Domain\Service\Trading\BondsService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BondsController extends Controller
{
    public function viewAction($bondsSymbol)
    {
        try {
            $bonds = BondsService::buildBonds($bondsSymbol);
        } catch (InvalidArgumentException $e) {
            throw $this->createNotFoundException("The bonds '{$bondsSymbol}' does not exist");
        }

        $balance = 100;
        $unitPrice = 104.5;
        $currency = 'LEI';

        $portfolio = PortfolioService::makePortfolio($balance, AmountService::buildAmount($unitPrice, $currency));

        $bondsEvolution = new BondsEvolutionService(new AmountService(), new InterestService());
        $bondsEvolution->setPrincipal($bonds);
        $bondsEvolution->setPortfolio($portfolio);
        $evolutions = $bondsEvolution->getPortfolioEvolution(
            DateTimeInterval::getToday(),
            new \DateInterval('P7D')
        );

        /**
         * @var \DateTime $dateStart
         * @var \DateTime $dateEnd
         */
        $dateStart = $dateEnd = null;
        $initialValue = $portfolio->getPrice()->getValue();

        $evolutionSeries = array_map(function (Evolution $evolution) use (&$dateStart, &$dateEnd) {
            if (null === $dateStart) {
                $dateStart = $evolution->getDate();
            }
            $dateEnd = $evolution->getDate();
            return [$evolution->getDate()->format('U') * 1000, $evolution->getAmount()->getValue()];
        }, $evolutions);

        $percentSeries = array_map(function (Evolution $evolution) use ($initialValue, $currency) {
            return [$evolution->getDate()->format('U') * 1000, $evolution->getAmount()->getValue() / $initialValue * 100];
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