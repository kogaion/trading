<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 3/1/2018
 * Time: 9:40 PM
 */

namespace AppBundle\Command;


use AppBundle\Domain\Model\Trading\Portfolio;
use AppBundle\Domain\Model\Util\Formatter;
use AppBundle\Domain\Model\Util\HttpException;
use AppBundle\Domain\Service\Trading\PortfolioService;
use Goutte\Client;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScreenPortfolioCommand extends TradevilleCommand
{
    /**
     * @var PortfolioService
     */
    private $portfolioService;
    
    /**
     * ScreenPortfolioCommand constructor.
     * @param null $name
     * @param PortfolioService $portfolioService
     */
    public function __construct($name = null, PortfolioService $portfolioService)
    {
        parent::__construct($name);
        $this->portfolioService = $portfolioService;
    }
    
    protected function configure()
    {
        $this->setName('screen:portfolio')
            ->setDescription('Get Portfolio from Tradeville')
            ->setHelp('Get Portfolio from Tradeville');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Connecting to Tradeville.");
        try {
            $client = $this->connect();
            
            $output->writeln("Connected. Going to portfolio page.");
            $symbols = $this->extractSymbolsFromPage($this->loadPortfolioPage($client));
            foreach ($symbols as $symbol) {
                
                $output->write('Extracting portfolios for ' . $symbol . '. ');
                $portfolios = $this->extractSymbolPortfolio($client, $symbol);
    
                $output->writeln('Saving ' . count($portfolios) . ' portfolios.');
                $this->savePortfolios($portfolios);
            }
            
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
     * @return \DOMElement[]
     * @throws HttpException
     */
    private function loadPortfolioPage(Client $client)
    {
        $crawler = $client->request('GET', $this->getContainer()->getParameter('tdv_url_portfolio'));
        if (empty(($crawler->filter('div#ctl00_c_ucAdvancedPortfolio_divSharesAll tr')->getIterator()->count()))) {
            throw new HttpException("Could not load shares screener", HttpException::ERR_URI_FAILED);
        }
    
        $tableRows = $crawler->filter('div#ctl00_c_ucAdvancedPortfolio_divSharesAll tr')->getIterator();
        return $tableRows;
    }
    
    /**
     * @param \DOMElement[] $symbolsIterator
     * @return string[]
     */
    private function extractSymbolsFromPage($symbolsIterator)
    {
        $symbols = [];
        
        $countSymbols = count($symbolsIterator);
        
        foreach ($symbolsIterator as $key => $row) {
            if ($row instanceof \DOMElement) {
    
                // exclude the last 3 rows - currency, total profit, total
                if ($key >= $countSymbols - 3) {
                    break;
                }
    
                $cells = $row->childNodes;
                if ($row->getAttribute('class') == 'header') {
                    continue;
                }
    
                $symbols[] = $cells->item(0)->nodeValue;
            }
        }
        return $symbols;
    }
    
    /**
     * @param Client $client
     * @param string $symbol
     * @return Portfolio[]
     * @throws HttpException
     */
    private function extractSymbolPortfolio(Client $client, $symbol)
    {
        $portfolios = [];
        $crawler = $client->request(
            'GET',
            $this->getContainer()->getParameter('tdv_url_symbol_portfolio') . '?' . http_build_query([
                'Symbol' => $symbol,
                'Tab' => 'Activity',
            ]));
        $iterator = $crawler->filter('table#ctl00_c_ctl00_gvShares tr')->getIterator();
        if (empty($iterator->count())) {
            throw new HttpException("Could not load portfolio for symbol {$symbol}", HttpException::ERR_URI_FAILED);
        }
    
        foreach ($iterator as $key => $row) {
            if (!($row instanceof \DOMElement)) {
                continue;
            }
    
            if ($row->getAttribute('class') == 'header') {
                continue;
            }
            
            $cells = $row->childNodes;
            
            $date = trim($cells->item(0)->nodeValue);
            $qty = trim($cells->item(4)->nodeValue);
            $value = Formatter::toDouble(trim($cells->item(5)->nodeValue));
            
            if ($value < 0) { // buy
                $price = -1 * $value / $qty;
                $portfolios[] = $this->portfolioService->makePortfolio($symbol, $qty, $price, $date);
            }
        }
        
        return $portfolios;
    }
    
    /**
     * @param Portfolio[] $portfolios
     */
    private function savePortfolios($portfolios)
    {
        foreach ($portfolios as $p) {
            $this->portfolioService->savePortfolio($p);
        }
    }
}