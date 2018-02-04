<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 10:28 PM
 */

namespace AppBundle\Presentation\Controller;


use AppBundle\Domain\Model\Util\DateTimeInterval;
use AppBundle\Domain\Model\Util\InvalidArgumentException;
use AppBundle\Domain\Service\Reporting\BondsEvolution;
use AppBundle\Domain\Service\Trading\AmountService;
use AppBundle\Domain\Service\Trading\PortfolioService;
use AppBundle\Domain\Service\Trading\BondsService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BondsController extends Controller
{
    public function viewAction($slug)
    {
        try {
            $bonds = BondsService::buildBonds($slug);
        } catch (InvalidArgumentException $e) {
            throw $this->createNotFoundException("The bonds '{$slug}' does not exist");
        }

        $portfolio = PortfolioService::makePortfolio(82, AmountService::buildAmount(104.89, 'LEI'));

        $bondsEvolution = new BondsEvolution(new AmountService());
        $bondsEvolution->setPrincipal($bonds);
        $bondsEvolution->setPortfolio($portfolio);
        $interests = $bondsEvolution->getEvolution(new \DateInterval('P1M'));
        print_r($interests);

        return $this->render("bonds/view.html.twig", [
            'principal' => $bonds->getSymbol(),
        ]);

    }
}