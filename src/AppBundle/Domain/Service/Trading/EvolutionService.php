<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 11:55 PM
 */

namespace AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\Evolution;

class EvolutionService
{
    /**
     * @param \DateTime $date
     * @param float $amount
     * @return Evolution
     */
    public static function makeEvolution(\DateTime $date, $amount)
    {
        return (new Evolution())->setDate($date)->setValue($amount);
    }
}