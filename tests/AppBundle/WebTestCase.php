<?php

namespace Tests\AppBundle;

/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/10/2018
 * Time: 5:22 PM
 */

use Mockery;

class WebTestCase extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
}