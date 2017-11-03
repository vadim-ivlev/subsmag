<?php

namespace Rg\ApiBundle\Repository;

/**
 * PatriffRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PatriffRepository extends \Doctrine\ORM\EntityRepository
{
    public function getDistinctDeliveries()
    {
        $qb = $this->createQueryBuilder('p');
        $result = $qb
            ->select('DISTINCT d.id, d.name, d.alias, d.description, d.sort')
            ->join('p.delivery', 'd')
            ->orderBy('d.sort')
            ->getQuery()
            ->getResult()
        ;

        return $result;
    }

    public function getDistinctYearsWithDelivery(int $delivery_id)
    {
//        "SELECT i.year FROM Rg\ApiBundle\Entity\Patriff p INNER JOIN p.delivery d INNER JOIN p.issue i WHERE d.id = :delivery_id GROUP BY i.year"
        $qb = $this->createQueryBuilder('p');
        $result = $qb
            ->select('i.year')
            ->join('p.delivery', 'd')
            ->join('p.issue', 'i')
            ->where($qb->expr()->eq('d.id', ':delivery_id'))
            ->groupBy('i.year')
            ->setParameter(':delivery_id', $delivery_id)
            ->getQuery()
            ->getResult()
        ;


        return $result;
    }

    public function findIssuesByYearAndDelivery(int $year, int $delivery_id, int $zone_id)
    {
/*
SELECT
    i.id,\n
    i.month,\n
    i.year,\n
    i.description,\n
    i.text,\n
    i.image\n
, p,i,s FROM Rg\ApiBundle\Entity\Patriff p
INNER JOIN p.delivery d
INNER JOIN p.issue i
INNER JOIN p.zone z
LEFT JOIN i.summaries s
WHERE d.id = :delivery_id AND i.year = :year AND z.id = :zone_id
*/
        $qb = $this->createQueryBuilder('p');
        $result = $qb
            ->select('
                i.id,
                i.month,
                i.year,
                i.description,
                i.text,
                i.image
            ')
            ->addSelect('p,i,s')
            ->join('p.delivery', 'd')
            ->join('p.issue', 'i')
            ->join('p.zone', 'z')
            ->leftJoin('i.summaries', 's')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('d.id', ':delivery_id'),
                    $qb->expr()->eq('i.year', ':year'),
                    $qb->expr()->eq('z.id', ':zone_id'),
                    $qb->expr()->eq('i.is_active', ':true')
                )
            )
            ->orderBy('i.year', 'ASC')
            ->addOrderBy('i.month', 'ASC')
            ->setParameter(':delivery_id', $delivery_id)
            ->setParameter(':year', $year)
            ->setParameter(':zone_id', $zone_id)
            ->setParameter(':true', 1)
            ->getQuery()
            ->getResult()
        ;

        return $result;
    }
}
