<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/16/2018
 * Time: 7:51 PM
 */

namespace AppBundle\Infrastructure\Repository;

use AppBundle\Domain\Model\Trading\Interest;
use AppBundle\Domain\Model\Trading\Portfolio;
use AppBundle\Domain\Model\Trading\PrincipalBonds;
use AppBundle\Domain\Repository\BondsRepository as Repo;
use Doctrine\ORM\EntityManagerInterface;


class BondsRepository extends EntityRepository implements Repo
{
    public function __construct(EntityManagerInterface $entityManager, $class = PrincipalBonds::class)
    {
        parent::__construct($entityManager, $class);
    }
    
    /**
     * @param $symbol
     * @return PrincipalBonds
     */
    public function loadBond($symbol)
    {
        return $this->findOneBy(['symbol' => $symbol]);
    }
    
    /**
     * @param PrincipalBonds $bond
     * @return bool
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function storeBond(PrincipalBonds $bond)
    {
        $manager = $this->getEntityManager();
        $manager->persist($bond);
        $manager->flush();
        
        return true;
    }
    
    /**
     * @return PrincipalBonds[]
     */
    public function loadBonds()
    {
        return $this->findBy([], ['symbol' => 'asc']);
    }
}