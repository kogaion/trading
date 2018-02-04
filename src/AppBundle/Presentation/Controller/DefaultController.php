<?php

namespace AppBundle\Presentation\Controller;

use AppBundle\Domain\Service\Trading\AmountService;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use AppBundle\Domain\Service\Trading\CurrencyService;
use AppBundle\Domain\Service\Trading\InterestService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $currency = CurrencyService::makeCurrency('LEI');
        $interest = InterestService::makeInterest(12, new \DateInterval('P1Y'));
        $amount = AmountService::makeAmount(4500, $currency);
        $evaluationInterval = DateTimeInterval::getToday()->diff(new \DateTime('2018-12-31'));

        $amountService = new AmountService();
        $amountFromInterest = $amountService->getAmountInterestForInterval($amount, $interest, $evaluationInterval);

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'interest'  => $amount->add($amountFromInterest)->getValue(),
            'currency'  => $currency->getSymbol()
        ]);
    }
}
