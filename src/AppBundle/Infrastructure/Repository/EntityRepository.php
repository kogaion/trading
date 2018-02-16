<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/15/2018
 * Time: 11:36 PM
 */

namespace AppBundle\Infrastructure\Repository;

/**
 * @see http://disq.us/p/1n6xhsr
 * @see https://github.com/park-manager/park-manager/blob/master/src/Module/Webhosting/Infrastructure/Doctrine/Repository/WebhostingDomainNameOrmRepository.php
 * @see https://github.com/park-manager/park-manager/blob/master/src/Bridge/Doctrine/EntityRepository.php
 */


use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;

abstract class EntityRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function __construct(EntityManagerInterface $entityManager, $className)
    {
        $this->_em = $entityManager;
        $this->_class = $entityManager->getClassMetadata($className);
        $this->_entityName = $className;
    
        try {
            Type::getType('type_date_interval');
        } catch (DBALException $ex) {
            Type::addType('type_date_interval', DateIntervalType::class);
        }
        
        
    }
    public function getModelClass()
    {
        return $this->_entityName;
    }
    protected function doTransactionalPersist($entity)
    {
        $this->_em->transactional(function () use ($entity) {
            $this->_em->persist($entity);
        });
    }
    protected function doTransactionalRemove($entity)
    {
        $this->_em->transactional(function () use ($entity) {
            $this->_em->remove($entity);
        });
    }
}