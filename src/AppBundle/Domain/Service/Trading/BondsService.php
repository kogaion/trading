<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 9:22 PM
 */

namespace AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\Amount;
use AppBundle\Domain\Model\Trading\Interest;
use AppBundle\Domain\Model\Trading\PrincipalBonds;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use AppBundle\Domain\Model\Util\InvalidArgumentException;
use AppBundle\Domain\Service\Crawling\BondsScreenerService;

class BondsService
{
    /**
     * @var AmountService
     */
    protected $amountService;
    /**
     * @var InterestService
     */
    protected $interestService;
    /**
     * @var BondsScreenerService
     */
    private $bondsScreenerService;
    
    /**
     * BondsService constructor.
     * @param AmountService $amountService
     * @param InterestService $interestService
     * @param BondsScreenerService $bondsScreenerService
     */
    public function __construct(
        AmountService $amountService,
        InterestService $interestService,
        BondsScreenerService $bondsScreenerService)
    {
        $this->amountService = $amountService;
        $this->interestService = $interestService;
        $this->bondsScreenerService = $bondsScreenerService;
    }
    
    /**
     * @param string $symbol
     * @param Interest $interest
     * @param Amount $faceValue
     * @param \DateTime $maturityDate
     * @return PrincipalBonds
     */
    public function makeBonds($symbol, Interest $interest, Amount $faceValue, \DateTime $maturityDate)
    {
        return (new PrincipalBonds())
            ->setSymbol($symbol)
            ->setInterest($interest)
            ->setFaceValue($faceValue)
            ->setMaturityDate($maturityDate);
    }
    
    /**
     * @param $bondsSymbol
     * @return PrincipalBonds
     */
    public function buildBonds($bondsSymbol)
    {
        $principal = $this->searchBonds($bondsSymbol);
        return $this->makeBonds(
            $principal[0],
            $this->interestService->makeInterest($principal[1], new \DateInterval($principal[2])),
            $this->amountService->buildAmount($principal[3], $principal[4]),
            DateTimeInterval::getDate($principal[5])
        );
    }
    
    /**
     * @return PrincipalBonds[]
     */
    public function listBonds()
    {
        $bonds = [];
        foreach ($this->loadBonds() as $bondsSymbol => $bondsDetails) {
            $bonds[$bondsSymbol] = $this->buildBonds($bondsSymbol);
        }
        return $bonds;
    }
    
    /**
     * @param $bondsSymbol
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function searchBonds($bondsSymbol)
    {
        $bonds = $this->loadBonds();
        
        if (!array_key_exists($bondsSymbol, $bonds)) {
            throw new InvalidArgumentException("Invalid bonds: {$bondsSymbol}", InvalidArgumentException::ERR_PRINCIPAL_INVALID);
        }
        
        return $bonds[$bondsSymbol];
    }

//    public function store
    
    /**
     * @return array
     */
    protected function loadBonds()
    {
        $bonds = $this->bondsScreenerService->loadBonds(['SBG20']);
        
        $return = [];
        foreach ($bonds as $bond) {
            $return[$bond->getSymbol()] = [
                $bond->getSymbol(),
                $bond->getInterest(),
                'P1Y', // @todo load from Repository
                (int) ($bond->getDirtyPrice() / $bond->getAsk()) * 100.00, // @todo load from Repository
                'LEI', // @todo load from Repository
                $bond->getMaturityDate()->format('Y-m-d')
            ];
        }
        
        return $return;
    }
}