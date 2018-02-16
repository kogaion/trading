<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/16/2018
 * Time: 10:06 AM
 */

namespace AppBundle\Domain\Service\Crawling;


use AppBundle\Domain\Model\Crawling\BondsScreener;
use AppBundle\Domain\Repository\BondsScreenerRepository;

class BondsScreenerService
{
    /**
     * @var BondsScreenerRepository
     */
    private $repository;
    
    public function __construct(BondsScreenerRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * @param BondsScreener[] $bonds
     * @return bool
     */
    public function saveBonds($bonds)
    {
        if (empty($bonds)) {
            return false;
        }
        
        $nonEmptyBonds = [];
        foreach ($bonds as $b) {
            if ($b->getAsk() == 0) {
                continue;
            }
            $nonEmptyBonds[] = $b;
        }
        
        return $this->repository->storeBonds($nonEmptyBonds);
    }
    
    /**
     * @return BondsScreener[]
     */
    public function loadBonds()
    {
        $bonds = $this->repository->loadBonds();
        if (empty($bonds)) {
            return [];
        }
        
        foreach ($bonds as $key => $b) {
            if ($b->getYTM() <= 0 || $b->getAsk() <= 0) {
                unset($bonds[$key]);
            }
        }
        
        return $bonds;
    }
}