<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 3/10/2018
 * Time: 3:33 PM
 */

namespace AppBundle\Domain\Service\Trading;


use AppBundle\Domain\Model\Trading\Share;
use AppBundle\Domain\Repository\SharesRepository;

class SharesService
{
    /**
     * @var SharesRepository
     */
    private $sharesRepository;
    
    public function __construct(SharesRepository $sharesRepository)
    {
        $this->sharesRepository = $sharesRepository;
    }
    
    /**
     * @return Share[]
     */
    public function listShares()
    {
        return [];
    }
}