<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/16/2018
 * Time: 5:33 PM
 */

namespace AppBundle\Infrastructure\Repository;

use AppBundle\Domain\Model\Trading\Portfolio;
use AppBundle\Domain\Repository\PortfolioRepository as Repo;
use Doctrine\ORM\EntityManagerInterface;

class PortfolioRepository extends EntityRepository implements Repo
{
    public function __construct(EntityManagerInterface $entityManager, $class = Portfolio::class)
    {
        parent::__construct($entityManager, $class);
    }
    
    /**
     * @return Portfolio[]
     */
    public function loadPortfolios()
    {
        return $this->findBy([], ['acquisitionDate' => 'ASC']);
    }
}