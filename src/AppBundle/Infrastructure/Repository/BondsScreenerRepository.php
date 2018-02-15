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
     * @param BondsScreener[] $bs
     * @return bool
     */
    public function storeBulk(array $bs)
    {
        $manager = $this->getEntityManager();
        foreach ($bs as $bondScreener) {
            if ($bondScreener instanceof BondsScreener) {
                $manager->persist($bondScreener);
            }
        }
        $manager->flush();
        return true;
    }
}