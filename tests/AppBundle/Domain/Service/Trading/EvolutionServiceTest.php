<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/10/2018
 * Time: 7:40 PM
 */

namespace Tests\AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\Evolution;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use AppBundle\Domain\Service\Trading\EvolutionService;
use Tests\AppBundle\TestCase;

class EvolutionServiceTest extends TestCase
{
    public function testFactoryReturnsEvolution()
    {
        $amount = 10;
        $date = DateTimeInterval::getDate('tomorrow');

        $instance = (new EvolutionService())->makeEvolution($date, $amount);

        $this->assertInstanceOf(Evolution::class, $instance);
        $this->assertEquals($amount, $instance->getValue());
        $this->assertEquals($date, $instance->getDate());
    }
}