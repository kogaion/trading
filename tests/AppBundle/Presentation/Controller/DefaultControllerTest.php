<?php

namespace Tests\AppBundle\Presentation\Controller;

use Tests\AppBundle\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

    public function dontTestIndex()
    {
        return;

//        $client = static::createClient();
//
//        $crawler = $client->request('GET', '/');
//
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        $this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
    }
}
