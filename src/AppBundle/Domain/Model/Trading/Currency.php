<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 12:00 AM
 */

namespace AppBundle\Domain\Model\Trading;


class Currency
{
    /**
     * @var string 3 letter code
     */
    protected $symbol;

    /**
     * @var int
     */
    protected $precision = 2;

    /**
     * @return int
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * @param string $symbol
     * @return Currency
     */
    public function setSymbol($symbol)
    {
        $this->symbol = strtoupper($symbol);
        return $this;
    }

    /**
     * @param int $precision
     * @return Currency
     */
    public function setPrecision($precision)
    {
        $this->precision = (int)$precision;
        return $this;
    }

    /**
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }
}