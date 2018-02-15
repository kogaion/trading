<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/15/2018
 * Time: 2:57 PM
 */

namespace AppBundle\Command;


use AppBundle\Domain\Model\Trading\BondScreener;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use AppBundle\Domain\Model\Util\HttpException;
use Goutte\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetBonds extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:get-bonds')
            ->setDescription('Get Bonds from Tradeville')
            ->setHelp('Get Bonds from Tradeville');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(["Connecting to Tradeville."]);
        try {
            $client = $this->connect();
            
            $output->writeln(["Connected. Going to bonds screener."]);
            $bondsIterator = $this->loadBondsScreener($client);
            
            $bonds = $this->loadBondsFromDOM($bondsIterator);
            print_r($bonds);
            
            $output->writeln(['Done.']);
        } catch (HttpException $e) {
            switch ($e->getCode()) {
                case HttpException::ERR_LOGIN_FAILED:
                    $output->writeln(['Login failed.']);
                    break;
                default:
                    $output->writeln('Unknown error: ' . $e->getMessage());
            }
        }
    }
    
    private function connect()
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
    
    /**
     * @param Client $client
     * @return \ArrayIterator
     * @throws HttpException
     */
    private function loadBondsScreener(Client $client)
    {
        $crawler = $client->request('GET', $this->getContainer()->getParameter('tdv_url_bonds_screener'));
        $crawler = $crawler->filter('div#ctl00_divAll tr');
        if (empty($crawler->getIterator()->count())) {
            throw new HttpException("Could not load bonds screener", HttpException::ERR_URI_FAILED);
        }
        
        return $crawler->getIterator();
    }
    
    /**
     * @param \ArrayIterator $tableRows
     * @return BondScreener[]
     */
    private function loadBondsFromDOM(\ArrayIterator $tableRows)
    {
        $bonds = [];
        
        foreach ($tableRows as $key => $row) {
            if ($row instanceof \DOMElement) {
                if ($row->getAttribute('class') == 'header') {
                    continue;
                }
                
                $cells = $row->childNodes;
                
                $i = 0;
                $bondScreener = new BondScreener();
                $bondScreener
                    ->setDate(DateTimeInterval::getToday())
                    ->setSymbol($cells->item($i++)->nodeValue)
                    ->setBidQty($cells->item($i++)->nodeValue)
                    ->setBid($cells->item($i++)->nodeValue)
                    ->setAsk($cells->item($i++)->nodeValue)
                    ->setAskQty($cells->item($i++)->nodeValue)
                    ->setDirtyPrice($cells->item($i++)->nodeValue)
                    ->setYTM($cells->item($i++)->nodeValue)
                    ->setMaturityDate($cells->item($i++)->nodeValue)
                    ->setSpreadDays($cells->item($i++)->nodeValue)
                    ->setInterest($cells->item($i++)->nodeValue);
                
                $bonds[$bondScreener->getSymbol()] = $bondScreener;
            }
        }
        
        return $bonds;
    }
}