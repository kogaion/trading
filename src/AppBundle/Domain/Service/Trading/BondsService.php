<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 9:22 PM
 */

namespace AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\PrincipalBonds;
use AppBundle\Domain\Model\Util\InvalidArgumentException;

class BondsService
{
    /**
     * @var array
     * @todo Load from repository
     */
    protected static $principals = [
        'SBG20' => ['SBG20', 12, 'P1Y', 100, 'LEI', '2020-01-15'],
        'FRU21' => ['FRU21', 9, 'P1Y', 100, 'LEI', '2021-03-14'],
        'CFS18' => ['CFS18', 8, 'P1Y', 100, 'LEI', '2018-11-27'],
        'BNET22' => ['CFS18', 9, 'P1Y', 100, 'LEI', '2022-09-08'],
        'BNET19' => ['BNET19', 9, 'P1Y', 100, 'LEI', '2019-06-15'],
        'ADRS18' => ['ADRS18', 10, 'P1Y', 100, 'LEI', '2018-10-23'],
        'INV22' => ['INV22', 7, 'P1Y', 100, 'LEI', '2022-03-23'],
    ];

    /**
     * @param $symbol
     * @return PrincipalBonds
     * @throws InvalidArgumentException
     * @todo return PrincipalBonds or PrincipalShares
     */
    public static function buildBonds($symbol)
    {
        /**
         * @todo load bond from Repository
         */
        if (!array_key_exists($symbol, self::$principals)) {
            throw new InvalidArgumentException("Invalid bonds: {$symbol}", InvalidArgumentException::ERR_PRINCIPAL_INVALID);
        }

        $principal = self::$principals[$symbol];
        return (new PrincipalBonds())
            ->setSymbol($principal[0])
            ->setInterest(InterestService::makeInterest($principal[1], new \DateInterval($principal[2])))
            ->setFaceValue(AmountService::buildAmount($principal[3], $principal[4]))
            ->setMaturityDate(new \DateTime($principal[5]));
    }

    /**
     * @return PrincipalBonds[]
     * @todo extract from Repository
     */
    public function listBonds()
    {
        $bonds = [];
        foreach (self::$principals as $bondsSymbol => $bondsDetails) {
            $bonds[$bondsSymbol] = self::buildBonds($bondsSymbol);
        }
        return $bonds;
    }
}