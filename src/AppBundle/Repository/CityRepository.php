<?php

namespace AppBundle\Repository;
use Apacz\FeedBundle\Entity\SchemaConnect;
use AppBundle\Entity\City;

/**
 * CityRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CityRepository extends \Doctrine\ORM\EntityRepository
{
    public function findOneByFeed(SchemaConnect $schemaConnect) : ?City
    {
        $qb = $this->createQueryBuilder('c');
        $qb->join('c.schemaConnect', 'f',  'WITH', 'f = :feed');
        $qb->setParameter(':feed', $schemaConnect->getId());
        $qb->addOrderBy('c.id', 'desc');
        return $qb->getQuery()->getOneOrNullResult();
    }
}
