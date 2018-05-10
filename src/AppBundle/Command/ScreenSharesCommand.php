<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/17/2018
 * Time: 9:45 PM
 */

namespace AppBundle\Command;


use AppBundle\Domain\Model\Crawling\SharesScreener;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use AppBundle\Domain\Model\Util\HttpException;
use AppBundle\Domain\Service\Crawling\SharesScreenerService;
use Goutte\Client;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Field\InputFormField;

class ScreenSharesCommand extends TradevilleCommand
{
    /**
     * @var SharesScreenerService
     */
    private $sharesScreenerService;
    
    public function __construct(
        $name = null,
        SharesScreenerService $sharesScreenerService
    )
    {
        parent::__construct($name);
        $this->sharesScreenerService = $sharesScreenerService;
    }
    
    protected function configure()
    {
        $this->setName('screen:shares')
            ->setDescription('Get Shares from Tradeville')
            ->setHelp('Get Shares from Tradeville');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Connecting to Tradeville.");
        try {
            $client = $this->connect();
            
            $output->writeln("Connected. Going to shares screener.");
            $crawler = $this->loadSharesScreener($client);
            
            $output->writeln("Extracting shares");
            $sharesScreener = $this->loadSharesScreenerFromDOM($crawler);
            
            $output->writeln("Saving " . count($sharesScreener) . " shares.");
            $this->sharesScreenerService->saveShares($sharesScreener);
            
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
    
    /**
     * @param Client $client
     * @return Crawler
     * @throws HttpException
     */
    private function loadSharesScreener(Client $client)
    {
        $crawler = $client->request('GET', $this->getContainer()->getParameter('tdv_url_shares_screener'));
        if (empty($crawler->filter('#ctl00_c_pl_ddlLists')->getIterator()->count())) {
            throw new HttpException("Could not load shares screener", HttpException::ERR_URI_FAILED);
        }
        
        $form = $crawler->filter('#aspnetForm')->form();
        
        $form['ctl00$c$pl$ddlLists']->select($this->getContainer()->getParameter('tdv_url_shares_screener_page_id'));
//        $form['__EVENTTARGET']->setValue('ctl00$c$pl$ddlLists');
//        $form['__EVENTARGUMENT']->setValue('');
        
        $domDocument = new \DOMDocument;
        $domInputTarget = $domDocument->createElement('input');
        $domInputTarget->setAttribute('name', '__EVENTTARGET');
        $domInputTarget->setAttribute('value', 'ctl00$c$pl$ddlLists');
        
        $domInputArgument = $domDocument->createElement('input');
        $domInputArgument->setAttribute('name', '__EVENTARGUMENT');
        $domInputArgument->setAttribute('value', '');
        
        $formInputTarget = new InputFormField($domInputTarget);
        $formInputArgument = new InputFormField($domInputArgument);
        $form->set($formInputTarget);
        $form->set($formInputArgument);
        
        $crawler = $client->submit($form);
        if (empty($crawler->filter('#ctl00_c_divPersonalListDetails')->getIterator()->count())) {
            throw new HttpException("Could not load special page", HttpException::ERR_URI_FAILED);
        }
        
        return $crawler;
    }
    
    /**
     * @param Crawler $crawler
     * @return SharesScreener[]
     */
    private function loadSharesScreenerFromDOM(Crawler $crawler)
    {
        $crawler = $crawler->filter('#ctl00_divAll div.boxbig.topmargin table.rows.whitebg')->first()->filter('tr');
        $tableRows = $crawler->getIterator();
        $shares = [];
        
        $referenceDate = '00/00/0000';
        foreach ($tableRows as $key => $row) {
            if ($row instanceof \DOMElement) {
                
                $cells = $row->childNodes;
                
                if ($row->getAttribute('class') == 'header') {
                    $referenceDateCell = $cells->item(4)->nodeValue;
                    $matches = null;
                    if (preg_match('/[^\d\/]+([\d\/]+)/', $referenceDateCell . "", $matches)) {
                        $referenceDate = $matches[1];
                    }
                    continue;
                }
                
                $sharesScreener = (new SharesScreener())
                    ->setSymbol($cells->item(0)->nodeValue)
                    ->setLastPrice($cells->item(2)->nodeValue)
                    ->setVariation($cells->item(3)->nodeValue)
                    ->setReferenceDate($referenceDate)
                    ->setReferencePrice($cells->item(4)->nodeValue)
                    ->setBid($cells->item(5)->nodeValue)
                    ->setAsk($cells->item(6)->nodeValue)
                    ->setDate(DateTimeInterval::getDate());;
                
                $shares[] = $sharesScreener;
                
            }
        }
        
        return $shares;
    }
    
    
}