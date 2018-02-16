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
use AppBundle\Domain\Repository\BondsRepository;
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
     * @var BondsRepository
     */
    private $bondsRepository;
    
    /**
     * BondsService constructor.
     * @param InterestService $interestService
     * @param BondsRepository $bondsRepository
     * @param BondsScreenerService $bondsScreenerService
     */
    public function __construct(
        InterestService $interestService,
        BondsRepository $bondsRepository,
        BondsScreenerService $bondsScreenerService)
    {
        $this->interestService = $interestService;
        $this->bondsScreenerService = $bondsScreenerService;
        $this->bondsRepository = $bondsRepository;
    }
    
    /**
     * @param string $symbol
     * @param Interest $interest
     * @param double $faceValue
     * @param \DateTime $maturityDate
     * @return PrincipalBonds
     */
    public function makeBond($symbol, Interest $interest, $faceValue, \DateTime $maturityDate)
    {
        return (new PrincipalBonds())
            ->setSymbol($symbol)
            ->setInterest($interest)
            ->setFaceValue($faceValue)
            ->setMaturityDate($maturityDate);
    }
    
    /**
     * @param PrincipalBonds $bond
     * @return bool
     */
    public function saveBond(PrincipalBonds $bond)
    {
        // @todo ignore Variable interest bonds, for now
        if ($bond->getInterest()->getType() == Interest::TYPE_VARIABLE) {
            return false;
        }
        
        return $this->bondsRepository->storeBond($bond);
    }
    
    /**
     * @param $bondsSymbol
     * @return PrincipalBonds
     * @throws InvalidArgumentException
     */
    public function buildBonds($bondsSymbol)
    {
        $bond = $this->bondsRepository->loadBond($bondsSymbol);
        if (!($bond instanceof PrincipalBonds) || $bond->getSymbol() != $bondsSymbol) {
            throw new InvalidArgumentException("Invalid bonds: {$bondsSymbol}", InvalidArgumentException::ERR_PRINCIPAL_INVALID);
        }
        return $bond;
    }
    
    /**
     * @return PrincipalBonds[]
     */
    public function listBonds()
    {
        return $this->bondsRepository->loadBonds();
    }
}