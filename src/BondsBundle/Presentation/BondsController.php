<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/14/2018
 * Time: 9:01 PM
 */

namespace BondsBundle\Presentation;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class BondsController extends Controller
{
    public function viewAction($bonds)
    {
        return $this->render("@Bonds/view.html.twig", [
        
        ]);
    }
}