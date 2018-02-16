<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/16/2018
 * Time: 7:51 PM
 */

namespace AppBundle\Infrastructure\Repository;

use AppBundle\Domain\Model\Trading\Portfolio;
use AppBundle\Domain\Repository\BondsRepository as Repo;
use Doctrine\ORM\EntityManagerInterface;



class BondsRepository extends EntityRepository implements Repo
{
    public function __construct(EntityManagerInterface $entityManager, $class = Portfolio::class)
    {
        parent::__construct($entityManager, $class);
    }
}