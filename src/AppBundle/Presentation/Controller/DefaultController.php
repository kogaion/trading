<?php

namespace AppBundle\Presentation\Controller;

use AppBundle\Domain\Model\Trading\AmountFactory;
use AppBundle\Domain\Model\Trading\CurrencyFactory;
use AppBundle\Domain\Model\Trading\InterestFactory;
use AppBundle\Domain\Model\Util\DateTimeInterval;
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
        $interest = InterestFactory::makeInterest(12, new \DateInterval('P1Y'));

        $evaluationInterval = DateTimeInterval::getToday()->diff(new \DateTime('2018-12-31'));
        $interest->setInterval($evaluationInterval);
        $val = $interest->getInterest(AmountFactory::makeAmount(4500, CurrencyFactory::makeCurrency('LEI', 2)));

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'interest'  => $val->getValue() + 4500,
            'currency'  => $val->getCurrency()->getSymbol()
        ]);
    }
}
