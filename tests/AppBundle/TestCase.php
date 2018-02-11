<?php

namespace Tests\AppBundle;

use AppBundle\Domain\Service\Trading\AmountService;
use AppBundle\Domain\Service\Trading\BondsService;
use AppBundle\Domain\Service\Trading\CurrencyService;
use AppBundle\Domain\Service\Trading\EvolutionService;
use AppBundle\Domain\Service\Trading\InflationService;
use AppBundle\Domain\Service\Trading\InterestService;
use AppBundle\Domain\Service\Trading\PortfolioService;
use Mockery;
use Mockery\Mock;

/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/10/2018
 * Time: 5:00 PM
 */

class TestCase extends
//        \Symfony\Bundle\FrameworkBundle\Tests\TestCase
        WebTestCase
{
    /**
     * @var Mock
     */
    protected $currencyService;
    /**
     * @var Mock
     */
    protected $amountService;
    /**
     * @var Mock
     */
    protected $interestService;
    /**
     * @var Mock
     */
    protected $bondsService;
    /**
     * @var Mock
     */
    protected $portfolioService;
    /**
     * @var Mock
     */
    protected $evolutionService;
    /**
     * @var Mock
     */
    protected $inflationService;

    public function tearDown() {
        Mockery::close();
        parent::tearDown();
    }

    public function setUp()
    {
        parent::setUp();

//        $this->currencyService = $this->getServiceMock('CurrencyService');
//        $this->amountService = $this->getServiceMock('AmountService');
//        $this->interestService = $this->getServiceMock('InterestService');
//        $this->bondsService = $this->getServiceMock('BondsService');
//        $this->portfolioService = $this->getServiceMock('PortfolioService');
//        $this->evolutionService = $this->getServiceMock('EvolutionService');
//        $this->inflationService = $this->getServiceMock('InflationService');

        $this->currencyService = Mockery::spy(CurrencyService::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $this->amountService = Mockery::spy(AmountService::class, [$this->currencyService])->makePartial()->shouldAllowMockingProtectedMethods();
        $this->interestService = Mockery::spy(InterestService::class, [$this->amountService])->makePartial()->shouldAllowMockingProtectedMethods();
        $this->bondsService = Mockery::spy(BondsService::class, [$this->amountService, $this->interestService])->makePartial()->shouldAllowMockingProtectedMethods();
        $this->portfolioService = Mockery::spy(PortfolioService::class, [$this->amountService])->makePartial()->shouldAllowMockingProtectedMethods();
        $this->evolutionService = Mockery::spy(EvolutionService::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $this->inflationService = Mockery::spy(InflationService::class)->makePartial()->shouldAllowMockingProtectedMethods();

    }

    /**
     * @param $serviceName
     * @return Mock
     */
    protected function getServiceMock($serviceName)
    {
        Mockery::spy();
        return (static::createClient()->getContainer()->get($serviceName . 'Mock')
            ->makePartial()
            ->shouldAllowMockingProtectedMethods());
    }


}