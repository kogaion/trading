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
     * BondsService constructor.
     * @param AmountService $amountService
     * @param InterestService $interestService
     */
    public function __construct(AmountService $amountService, InterestService $interestService)
    {
        $this->amountService = $amountService;
        $this->interestService = $interestService;
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

    /**
     * @return array
     * @todo load from Repository
     */
    protected function loadBonds()
    {
        return [
            'SBG20' => ['SBG20', 12, 'P1Y', 100, 'LEI', '2020-01-15'],
            'FRU21' => ['FRU21', 9, 'P1Y', 100, 'LEI', '2021-03-14'],
            'CFS18' => ['CFS18', 8, 'P1Y', 1000, 'LEI', '2018-11-27'],
            'BNET22' => ['BNET22', 9, 'P1Y', 100, 'LEI', '2022-09-08'],
            'BNET19' => ['BNET19', 9, 'P1Y', 1000, 'LEI', '2019-06-15'],
            'ADRS18' => ['ADRS18', 10, 'P1Y', 1000, 'LEI', '2018-10-23'],
            'INV22' => ['INV22', 7, 'P1Y', 90, 'LEI', '2022-03-23'],
        ];
    }
}