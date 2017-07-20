<?php

namespace Rg\ApiBundle\Repository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    public function getProductsWithMinPricesByArea(int $area_id)
    {
        $dql = <<<DQL
SELECT
    p,
    p.id,
    p.name,
    p.description,
    p.postal_index,
    p.is_kit,
    p.is_archive,
    p.outer_link,
    p.sort,
    min(t.price) AS min_price,
    e AS editions
FROM RgApiBundle:Product p
JOIN p.tariffs t
JOIN t.zone z
JOIN z.areas a 
JOIN p.editions e
WHERE a.id = :area_id
GROUP BY p.id, e.id
DQL;

        $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter('area_id', $area_id)
        ;
        $result = $query->getResult();

        return $result;
    }

}
