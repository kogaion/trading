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
use AppBundle\Domain\Repository\PortfolioSearch;
use Doctrine\ORM\EntityManagerInterface;

class PortfolioRepository extends EntityRepository implements Repo
{
    public function __construct(EntityManagerInterface $entityManager, $class = Portfolio::class)
    {
        parent::__construct($entityManager, $class);
    }
    
    /**
     * @param PortfolioSearch|null $search
     * @return Portfolio[]
     */
    public function loadPortfolios(PortfolioSearch $search = null)
    {
        $findBy = [];
        if (!empty($search)) {
            if (null !== $search->symbol) {
                $findBy['symbol'] = $search->symbol;
            }
        }
        return $this->findBy($findBy, ['acquisitionDate' => 'ASC']);
    }
    
    /**
     * @param $symbol
     * @return Portfolio
     */
//    public function loadPortfolio($symbol)
//    {
//        return $this->findOneBy(['symbol' => $symbol]);
//    }
}