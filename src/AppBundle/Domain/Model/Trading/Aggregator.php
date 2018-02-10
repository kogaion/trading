<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/3/2018
 * Time: 8:02 PM
 */

namespace AppBundle\Domain\Model\Trading;

class Aggregator
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var Principal[]
     */
    protected $principals;

    /**
     * @param string $code
     * @return Aggregator
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param Principal[] $principals
     * @return Aggregator
     */
    public function setPrincipals($principals)
    {
        $this->principals = $principals;
        return $this;
    }

    /**
     * @return Principal[]
     */
    public function getPrincipals()
    {
        return $this->principals;
    }
}