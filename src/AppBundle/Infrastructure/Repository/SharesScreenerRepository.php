<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/17/2018
 * Time: 10:53 PM
 */

namespace AppBundle\Infrastructure\Repository;

use AppBundle\Domain\Model\Crawling\SharesScreener;
use AppBundle\Domain\Repository\SharesScreenerRepository as Repo;
use Doctrine\ORM\EntityManagerInterface;

class SharesScreenerRepository extends EntityRepository implements Repo
{
    public function __construct(EntityManagerInterface $entityManager, $class = SharesScreener::class)
    {
        parent::__construct($entityManager, $class);
    }
    
    /**
     * @param SharesScreener[] $shares
     * @return bool
     */
    public function storeShares(array $shares)
    {
        $manager = $this->getEntityManager();
        foreach ($shares as $screener) {
            if ($screener instanceof SharesScreener) {
                if (!$this->findOneBy([
                    'screenDate' => $screener->getScreenDate(),
                    'symbol' => $screener->getSymbol(),
                    'ask' => $screener->getAsk(),
                    'lastPrice' => $screener->getLastPrice(),
                    'referenceDate' => $screener->getReferenceDate(),
                    'referencePrice' => $screener->getReferencePrice()
                ])) {
                    $manager->persist($screener);
                }
            }
        }
        $manager->flush();
        return true;
    }
    
    /**
     * @return SharesScreener[]
     */
    public function loadShares()
    {
        $manager = $this->getEntityManager();
        
        $builder = $this->createResultSetMappingBuilder("t");
        
        $query = $manager->createNativeQuery("
            select {$builder->generateSelectClause("t")}
            from (
                select *
                from shares_screener
                order by screen_date desc, stamp desc
            ) t
            group by symbol
            
        ", $builder);
        
        return $query->getResult();
    }
    
    /**
     * @param $symbol
     * @return SharesScreener
     */
    public function loadShare($symbol)
    {
        return $this->findOneBy([
            'symbol'    => $symbol,
        ], [
            'screenDate'    => 'desc',
            'date'         => 'desc'
        ]);
    }
}