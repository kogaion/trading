<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/15/2018
 * Time: 3:26 PM
 */

namespace AppBundle\Domain\Model\Util;


class HttpException extends Exception
{
    const ERR_LOGIN_FAILED = 3000;
    const ERR_URI_FAILED = 3001;
}