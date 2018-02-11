<?php

namespace AppBundle\Presentation\Controller;

use AppBundle\Domain\Service\Trading\InterestService;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use AppBundle\Domain\Service\Trading\AmountService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, InterestService $interestService, AmountService $amountService)
    {
        $currency = 'LEI';
        $interest = $interestService->makeInterest(12, new \DateInterval('P1Y'));
        $amount = $amountService->buildAmount(4500, $currency);
        $evaluationInterval = DateTimeInterval::getToday()->diff(DateTimeInterval::getDate('2018-12-31'));

        $amountFromInterest = $interestService->getInterestForInterval($amount, $interest, $evaluationInterval);

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'interest'  => $amount->add($amountFromInterest)->getValue(),
            'currency'  => $currency,
        ]);
    }
}
