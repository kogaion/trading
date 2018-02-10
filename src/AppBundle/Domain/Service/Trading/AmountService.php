<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 12:21 PM
 */

namespace AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\Amount;
use AppBundle\Domain\Model\Trading\Currency;
use AppBundle\Domain\Model\Util\InvalidArgumentException;

class AmountService
{
    /**
     * @var CurrencyService
     */
    protected $currencyService;

    /**
     * AmountService constructor.
     * @param CurrencyService $currencyService
     */
    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * @param float $value
     * @param Currency $currency
     * @return Amount
     */
    public function makeAmount($value, $currency)
    {
        return (new Amount())->setValue($value)->setCurrency($currency);
    }

    /**
     * @facade
     * @param float $value
     * @param string $currencySymbol
     * @return Amount
     * @throws InvalidArgumentException
     */
    public function buildAmount($value, $currencySymbol)
    {
        return (new Amount())->setValue($value)->setCurrency($this->currencyService->buildCurrency($currencySymbol));
    }

}