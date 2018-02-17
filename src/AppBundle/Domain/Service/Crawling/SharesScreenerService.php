<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/17/2018
 * Time: 10:49 PM
 */

namespace AppBundle\Domain\Service\Crawling;


use AppBundle\Domain\Model\Crawling\SharesScreener;
use AppBundle\Domain\Repository\SharesScreenerRepository;

class SharesScreenerService
{
    /**
     * @var SharesScreenerRepository
     */
    private $repository;
    
    public function __construct(SharesScreenerRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * @param SharesScreener[] $shares
     * @return bool
     */
    public function saveShares($shares)
    {
        if (empty($shares)) {
            return false;
        }
        
        return $this->repository->storeShares($shares);
    }
    
    /**
     * @return SharesScreener[]
     */
    public function loadShares()
    {
        $shares = $this->repository->loadShares();
        if (empty($shares)) {
            return [];
        }
        
        return $shares;
    }
}