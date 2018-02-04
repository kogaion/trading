<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 9:22 PM
 */

namespace AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\Principal;
use AppBundle\Domain\Model\Trading\PrincipalBonds;
use AppBundle\Domain\Model\Util\InvalidArgumentException;

class BondsService
{
    /**
     * @param $symbol
     * @return PrincipalBonds
     * @throws InvalidArgumentException
     * @todo return PrincipalBonds or PrincipalShares
     */
    public static function buildBonds($symbol)
    {
        /**
         * @todo - load from Repository
         */
        static $principals = [
            'SBG20' => ['SBG20', 12, 'P1Y', 100, 'LEI', '2020-01-15']
        ];
        if (!array_key_exists($symbol, $principals)) {
            throw new InvalidArgumentException("Invalid bonds: {$symbol}", InvalidArgumentException::ERR_PRINCIPAL_INVALID);
        }

        return (new PrincipalBonds())
            ->setSymbol($principals[$symbol][0])
            ->setInterest(InterestService::makeInterest($principals[$symbol][1], new \DateInterval($principals[$symbol][2])))
            ->setFaceValue(AmountService::buildAmount($principals[$symbol][3], $principals[$symbol][4]))
            ->setMaturityDate(new \DateTime($principals[$symbol][5]));
    }
}