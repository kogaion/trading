<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/4/2018
 * Time: 8:36 PM
 */

namespace AppBundle\Domain\Model\Util;


class InvalidOperationException extends Exception
{
    const ERR_CURRENCY_MISMATCH = 1000;
}