<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/15/2018
 * Time: 8:15 PM
 */

namespace AppBundle\Infrastructure\Repository;


use AppBundle\Domain\Model\Crawling\BondsScreener;
use AppBundle\Domain\Repository\BondsScreenerRepository as Repo;
use Doctrine\ORM\EntityManagerInterface;


class BondsScreenerRepository extends EntityRepository implements Repo
{
    public function __construct(EntityManagerInterface $entityManager, $class = BondsScreener::class)
    {
        parent::__construct($entityManager, $class);
    }
    
    /**
     * @param BondsScreener[] $bonds
     * @return bool
     */
    public function storeBonds(array $bonds)
    {
        $manager = $this->getEntityManager();
        foreach ($bonds as $screener) {
            if ($screener instanceof BondsScreener) {
                if (!$this->findOneBy([
                    'screenDate' => $screener->getScreenDate(),
                    'YTM' => $screener->getYTM(),
                    'symbol' => $screener->getSymbol(),
                    'askQty' => $screener->getAskQty(),
                    'dirtyPrice' => $screener->getDirtyPrice(),
                    'spreadDays' => $screener->getSpreadDays()
                ])) {
                    $manager->persist($screener);
                }
            }
        }
        $manager->flush();
        return true;
    }
    
    /**
     * @return BondsScreener[]
     */
    public function loadBonds()
    {
        $manager = $this->getEntityManager();
        
        $builder = $this->createResultSetMappingBuilder("t");
        
        $query = $manager->createNativeQuery("
            select {$builder->generateSelectClause("t")}
            from (
                select *
                from bonds_screener
                order by screen_date desc, stamp desc
            ) t
            group by symbol
            order by ytm
            
        ", $builder);
        
        return $query->getResult();
    }
    
    /**
     * @param $symbol
     * @return BondsScreener
     */
    public function loadBond($symbol)
    {
        return $this->findOneBy([
            'symbol'    => $symbol,
        ], [
            'screenDate'    => 'desc',
            'date'         => 'desc'
        ]);
    }
}