<?php

namespace Rg\ApiBundle\Repository;

use Doctrine\ORM\Query\Expr;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    public function getProductsWithMinPricesByArea(int $from_front_id)
    {
        /*
        SELECT
            p,
            p.id,
            p.name,
            p.description,
            p.text,
            p.postal_index,
            p.is_kit,
            p.is_archive,
            p.outer_link,
            p.sort
        , s,t,d,m,z,a,e,month FROM Rg\ApiBundle\Entity\Product p
        LEFT JOIN p.sales s
        LEFT JOIN s.month month
        LEFT JOIN p.tariffs t
        LEFT JOIN t.delivery d
        LEFT JOIN t.medium m
        LEFT JOIN t.zone z
        LEFT JOIN z.areas a WITH a.from_front_id = :from_front_id OR a.from_front_id IS NULL
        INNER JOIN p.editions e
        ORDER BY p.sort ASC, month.year ASC, month.number ASC
         */
        $qb = $this->createQueryBuilder('p');
        $result = $qb
            ->select('
                p,
                p.id,
                p.name,
                p.description,
                p.text,
                p.is_kit,
                p.is_archive,
                p.outer_link,
                p.is_popular,
                p.sort
            ')
            ->addSelect('s,t,d,m,z,a,e,month')
            ->leftJoin('p.sales', 's')
            ->leftJoin('s.month', 'month')
            ->leftJoin('p.tariffs', 't')
            ->leftJoin('t.delivery', 'd')
            ->leftJoin('t.medium', 'm')
            ->leftJoin('t.zone', 'z')
            ->leftJoin(
                'z.areas',
                'a',
                Expr\Join::WITH,
                $qb->expr()->orX(
                    $qb->expr()->eq('a.from_front_id', ':from_front_id'),
                    $qb->expr()->isNull('a.from_front_id')
                )
            )
            ->join('p.editions', 'e')
            ->orderBy('p.sort')
            ->addOrderBy('month.year')
            ->addOrderBy('month.number')
            ->setParameter('from_front_id', $from_front_id)
            ->getQuery()
//            ->getDQL(); echo $result;die; # for test purpose
            ->getResult()
        ;

        return $result;
    }

}
