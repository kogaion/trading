<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/17/2018
 * Time: 9:48 PM
 */

namespace AppBundle\Command;

use Goutte\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


abstract class TradevilleCommand extends ContainerAwareCommand
{
    /**
     * @return Client
     */
    protected function connect()
    {
        $client = new Client();
        $crawler = $client->request('GET', $this->getContainer()->getParameter('tdv_url_login'));
        
        $form = $crawler->selectButton('Login')->form();
        $form['cont'] = $this->getContainer()->getParameter('tdv_cont');
        $form['pw'] = $this->getContainer()->getParameter('tdv_pw');
        $form['platformType']->select('rbSt20');
        $crawler = $client->submit($form);
        
        $crawler = $crawler->filter('span#ctl00_h_ucLogin_lblHomeNume');
        if (empty($crawler)) {
            throw new HttpException("Could not login.", HttpException::ERR_LOGIN_FAILED);
        }
        
        return $client;
    }
}