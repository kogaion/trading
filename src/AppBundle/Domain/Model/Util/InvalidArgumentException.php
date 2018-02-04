<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 9:44 PM
 */

namespace AppBundle\Domain\Model\Util;


class InvalidArgumentException extends Exception
{
    const ERR_CURRENCY_INVALID = 2000;
    const ERR_PRINCIPAL_INVALID = 2001;
}