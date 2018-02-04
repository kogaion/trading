<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 11:55 PM
 */

namespace AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\Amount;
use AppBundle\Domain\Model\Trading\Evolution;

class EvolutionService
{
    public static function makeEvolution(\DateTime $date, Amount $amount)
    {
        return (new Evolution())->setDate($date)->setAmount($amount);
    }
}