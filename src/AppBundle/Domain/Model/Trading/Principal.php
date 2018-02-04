<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/3/2018
 * Time: 8:11 PM
 */

namespace AppBundle\Domain\Model\Trading;


abstract class Principal
{
    /**
     * @var string
     */
    protected $symbol;

    /**
     * @param string $symbol
     * @return $this
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
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