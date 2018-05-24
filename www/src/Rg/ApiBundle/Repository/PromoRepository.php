<?php

namespace Rg\ApiBundle\Repository;

/**
 * PromoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PromoRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllActive()
    {
        $qb = $this->createQueryBuilder('p');
        $result = $qb
            ->select('
                p
            ')
            ->andWhere(
                $qb->expr()->eq('p.is_active', ':tr')
            )
            ->andWhere(
                $qb->expr()->eq('p.is_visible', ':tr')
            )
            ->setParameter('tr', 1)
            ->getQuery()
//            ->getDQL(); echo $result;die; # for test purpose
            ->getResult()
        ;

        return $result;
    }
}
