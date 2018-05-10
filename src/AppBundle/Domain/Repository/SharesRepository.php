<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 3/10/2018
 * Time: 3:35 PM
 */

namespace AppBundle\Domain\Repository;


use AppBundle\Domain\Model\Trading\Share;

interface SharesRepository
{
    /**
     * @return Share[]
     */
    public function loadShares();
    
}