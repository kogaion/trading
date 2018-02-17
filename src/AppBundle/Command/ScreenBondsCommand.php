<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/15/2018
 * Time: 2:57 PM
 */

namespace AppBundle\Command;


use AppBundle\Domain\Model\Crawling\BondsScreener;
use AppBundle\Domain\Model\Trading\Interest;
use AppBundle\Domain\Model\Trading\PrincipalBonds;
use AppBundle\Domain\Model\Util\DateTimeInterval;
use AppBundle\Domain\Model\Util\Formatter;
use AppBundle\Domain\Model\Util\HttpException;
use AppBundle\Domain\Model\Util\InvalidArgumentException;
use AppBundle\Domain\Service\Crawling\BondsScreenerService;
use AppBundle\Domain\Service\Trading\BondsService;
use AppBundle\Domain\Service\Trading\InterestService;
use Goutte\Client;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class ScreenBondsCommand extends TradevilleCommand
{
    /**
     * @var BondsScreenerService
     */
    protected $bondsScreenerService;
    /**
     * @var BondsService
     */
    protected $bondsService;
    /**
     * @var InterestService
     */
    private $interestService;
    
    
    public function __construct(
        $name = null,
        BondsScreenerService $bondsScreenerService,
        BondsService $bondsService,
        InterestService $interestService)
    {
        parent::__construct($name);
        $this->bondsScreenerService = $bondsScreenerService;
        $this->bondsService = $bondsService;
        $this->interestService = $interestService;
    }
    
    protected function configure()
    {
        $this->setName('screen:bonds')
            ->setDescription('Get Bonds from Tradeville')
            ->setHelp('Get Bonds from Tradeville');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(["Connecting to Tradeville."]);
        try {
            $client = $this->connect();
            
            $output->writeln(["Connected. Going to bonds screener."]);
            $crawler = $this->loadBondsScreener($client);
            
            $output->writeln(["Extracting bonds."]);
            $bondsScreeners = $this->loadBondsScreenerFromDOM($crawler);
            
            foreach ($bondsScreeners as $key => $bs) {
                try {
                    // check if exists
                    $this->bondsService->buildBonds($bs->getSymbol());
                } catch (InvalidArgumentException $ex) {
                    
                    $output->write(["Found new bond {$bs->getSymbol()}."]);
                    $bondCrawler = $this->loadBonds($client, $bs->getSymbol());
                    
                    $output->write(["Extracting bond {$bs->getSymbol()}."]);
                    $bonds = $this->loadBondsFromDOM($bondCrawler, $bs->getSymbol());
                    
                    $output->write(["Saving new bond {$bs->getSymbol()}."]);
                    if (!$this->bondsService->saveBond($bonds)) {
                        $output->writeln("Skipping bond {$bonds->getSymbol()} for now.");
                        unset($bondsScreeners[$key]);
                        continue;
                    }
                    $output->writeln("Saved bond {$bonds->getSymbol()}.");
                }
            }
            
            
            $output->writeln(["Saving " . count($bondsScreeners) . " items."]);
            $this->bondsScreenerService->saveBonds($bondsScreeners);
            
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
    private function loadBondsScreener(Client $client)
    {
        $crawler = $client->request('GET', $this->getContainer()->getParameter('tdv_url_bonds_screener'));
        $crawler = $crawler->filter('#ctl00_divAll');
        if (empty($crawler->getIterator()->count())) {
            throw new HttpException("Could not load bonds screener", HttpException::ERR_URI_FAILED);
        }
        
        return $crawler;
    }
    
    
    /**
     * @param Crawler $crawler
     * @return BondsScreener[]
     */
    private function loadBondsScreenerFromDOM(Crawler $crawler)
    {
        $crawler = $crawler->filter('#ctl00_divAll tr');
        $tableRows = $crawler->getIterator();
        $bonds = [];
        
        foreach ($tableRows as $key => $row) {
            if ($row instanceof \DOMElement) {
    
                $cells = $row->childNodes;
                
                if ($row->getAttribute('class') == 'header') {
                    continue;
                }
                
                $i = 0;
                $bondScreener = new BondsScreener();
                $bondScreener
                    ->setDate(DateTimeInterval::getDate())
                    ->setSymbol($cells->item($i++)->nodeValue)
                    ->setBidQty($cells->item($i++)->nodeValue)
                    ->setBid($cells->item($i++)->nodeValue)
                    ->setAsk($cells->item($i++)->nodeValue)
                    ->setAskQty($cells->item($i++)->nodeValue)
                    ->setDirtyPrice($cells->item($i++)->nodeValue)
                    ->setYTM($cells->item($i++)->nodeValue)
                    ->setSpreadDays($cells->item($i++)->nodeValue);
                $bonds[] = $bondScreener;
            }
        }
        
        return $bonds;
    }
    
    private function loadBonds(Client $client, $symbol)
    {
        $crawler = $client->request(
            'GET',
            $this->getContainer()->getParameter('tdv_url_bonds') . '?' . http_build_query(['Symbol' => $symbol])
        );
        $cr = $crawler->filter('div.pageHeader h1')->first();
        if (false === stripos($cr->text(), $symbol)) {
            throw new HttpException("Could not load bonds {$symbol}", HttpException::ERR_URI_FAILED);
        }
        
        return $crawler;
    }
    
    /**
     * @param Crawler $crawler
     * @param $symbol
     * @return PrincipalBonds
     */
    private function loadBondsFromDOM(Crawler $crawler, $symbol)
    {
        $interestInterval = 'P1Y'; // @todo - default ?
        
        $interest = $crawler->filter("span#ctl00_c_ucBondsSymbolExtraInfo_QuotesBond_lblCurentFeeVal")->first()->text();
        $interestType = $crawler->filter("span#ctl00_c_ucBondsSymbolExtraInfo_QuotesBond_lblIntrestTypeValue")->first()->text();
        $faceValue = $crawler->filter("span#ctl00_c_ucBondsSymbolExtraInfo_QuotesBond_lblValueVal")->first()->text();
        $maturityDate = $crawler->filter("span#ctl00_c_ucBondsSymbolExtraInfo_QuotesBond_lblEndDateValue")->first()->text();
        
        $bond = $this->bondsService->makeBond(
            $symbol,
            $this->interestService->makeInterest(
                Formatter::toDouble($interest),
                new \DateInterval($interestInterval),
                (false !== stripos($interestType, 'fix') ? Interest::TYPE_FIXED : Interest::TYPE_VARIABLE)),
            $faceValue,
            Formatter::toDateTime($maturityDate)
        );
        
        return $bond;
    }
}