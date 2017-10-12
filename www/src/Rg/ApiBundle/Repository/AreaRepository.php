<?php

namespace Rg\ApiBundle\Repository;

/**
 * AreaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AreaRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAreaWithNotEmptyWorkId()
    {
        $qb = $this->createQueryBuilder('a');
        $result = $qb
            ->select('a')
            ->andWhere("a.works_id <> ''")
            ->getQuery()
            ->getResult()
        ;

        return $result;
    }

    public function getAreaWithNotEmptyWorkIdOrderedByWorkId()
    {
        $qb = $this->createQueryBuilder('a');
        $result = $qb
            ->select('a')
            ->andWhere("a.works_id <> ''")
            ->orderBy('a.works_id')
            ->getQuery()
            ->getResult()
        ;

        return $result;
    }

    public function getCitiesStep2()
    {
        $qb = $this->createQueryBuilder('a');
        $result = $qb
            ->select('a')
            ->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->isNull('a.parent_area'),
                    $qb->expr()->eq('a.works_id', ':str')
                )
            )
            ->orderBy('a.id')
            ->setParameter('str', '')
            ->getQuery()
            ->getResult()
        ;

        return $result;
    }

    public function getCitiesStep1()
    {
        $qb = $this->createQueryBuilder('a');
        $result = $qb
            ->select('a')
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->isNull('a.works_id'),
                    $qb->expr()->eq('a.works_id', ':str')
                )
            )
            ->orderBy('a.works_id')
            ->setParameter('str', '')
            ->getQuery()
            ->getResult()
        ;

        return $result;
    }
}
