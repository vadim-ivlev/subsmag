<?php

namespace Rg\ApiBundle\Repository;

/**
 * ContentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContentRepository extends \Doctrine\ORM\EntityRepository
{
    public function getSingle()
    {
        $qb = $this->createQueryBuilder('c');
        $result = $qb
            ->select('
                c
            ')
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $result;
    }
}
