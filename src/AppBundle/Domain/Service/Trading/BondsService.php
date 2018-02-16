<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 9:22 PM
 */

namespace AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\Interest;
use AppBundle\Domain\Model\Trading\PrincipalBonds;
use AppBundle\Domain\Model\Util\InvalidArgumentException;
use AppBundle\Domain\Service\Crawling\BondsScreenerService;

class BondsService
{
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
     * @param InterestService $interestService
     * @param BondsScreenerService $bondsScreenerService
     */
    public function __construct(
        InterestService $interestService,
        BondsScreenerService $bondsScreenerService)
    {
        $this->interestService = $interestService;
        $this->bondsScreenerService = $bondsScreenerService;
    }
    
    /**
     * @param string $symbol
     * @param Interest $interest
     * @param double $faceValue
     * @param \DateTime $maturityDate
     * @return PrincipalBonds
     */
    public function makeBonds($symbol, Interest $interest, $faceValue, \DateTime $maturityDate)
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
     * @throws InvalidArgumentException
     */
    public function buildBonds($bondsSymbol)
    {
        $bonds = $this->listBonds();
        if (array_key_exists($bondsSymbol, $bonds)) {
            return $bonds[$bondsSymbol];
        }
        throw new InvalidArgumentException("Invalid bonds: {$bondsSymbol}", InvalidArgumentException::ERR_PRINCIPAL_INVALID);
    }
    
    /**
     * @return PrincipalBonds[]
     */
    public function listBonds()
    {
        $bonds = $this->bondsScreenerService->loadBonds(['SBG20']); // @todo -> get the portfolio bonds always
    
        $return = [];
        foreach ($bonds as $bond) {
            $return[$bond->getSymbol()] = $this->makeBonds(
                $bond->getSymbol(),
                $this->interestService->makeInterest($bond->getInterest(), new \DateInterval('P1Y')), // @todo extract separately
                (int) ($bond->getDirtyPrice() / $bond->getAsk()) * 100.00, // @todo extract separately
                $bond->getMaturityDate()
            );
        }
    
        return $return;
    }
}