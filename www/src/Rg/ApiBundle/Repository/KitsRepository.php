<?php

namespace Rg\ApiBundle\Repository;

use Rg\ApiBundle\Controller\DataProcessing as Data;
use Rg\ApiBundle\Controller\Outer as Out;
use Rg\ApiBundle\Controller\Relations;

/**
 * KitsRepository
 *
 */
class KitsRepository extends \Rg\ApiBundle\Repository\Relations
{

//    /**
//     * @param $entity_id
//     * @param $kit_id
//     * @param $entity_name - В единственном числе!
//     * @return bool|\Symfony\Component\HttpFoundation\JsonResponse
//     * @throws \Doctrine\DBAL\DBALException
//     */
//    public function addRelationToKit($entity_id, $kit_id, $entity_name)
//    {
//        $out = new Out();
//
//        $conn = $this->getEntityManager()->getConnection();
//
//        $sql = "INSERT INTO `kits_{$entity_name}s` (`kit_id`, `{$entity_name}_id`) VALUES ({$kit_id}, {$entity_id});";
//
//        $stmt = $conn->prepare($sql);
//        try {
//
//            $stmt->execute();
//            $response = true;
//
//        } catch (\Exception $e) {
//
//            if ($e->getErrorCode() > 0) {
//                $response = $out->json([
//                    'status' => "error",
//                    'description' => $e->getMessage(),
//                    'sqlState' => $e->getSQLState(),
//                    'errorCode' => $e->getErrorCode(),
//                    'id' => null
//                ]);
////                var_dump($stmt->fetchAll());
////                var_dump($response);
//            }
//        }
//
//        return $response;
//    }

    /**
     * @param $product_id
     * @param $kit_id
     */
    public function updateOneProductInTheKit($product_id, $kit_id, $new_product_id, $new_kit_id)
    {
        $out = new Out();

        $conn = $this->getEntityManager()->getConnection();

        //если надо удалить все издания из комплекта
        $sql = "UPDATE kits_products SET kit_id = {$new_kit_id}, product_id = {$new_product_id} WHERE kit_id = {$kit_id} AND product_id = {$product_id};";

        $stmt = $conn->prepare($sql);
        try {

            $stmt->execute();
            $response = true;

        } catch (\Exception $e) {

            if ($e->getErrorCode() > 0) {
                $response = $out->json([
                    'status' => "error",
                    'description' => $e->getMessage(),
                    'sqlState' => $e->getSQLState(),
                    'errorCode' => $e->getErrorCode(),
                    'id' => null
                ]);
//                var_dump($stmt->fetchAll());
                var_dump($response);
            }
        }

        return $response;
    }

//    /**
//     * Если передать product_id = 0 (int), то удаляются привязки для нужного комплекта
//     * Если передать kit_id = 0 (int), то удаляются привязки для нужного издания
//     * Флаг rmAllFromKit (bool) - предохранитель от случайного удаления всего подряд
//     *
//     * @param $product_id
//     * @param $kit_id
//     * @param bool $rmAllFromKit
//     * @return bool|\Symfony\Component\HttpFoundation\JsonResponse
//     * @throws \Doctrine\DBAL\DBALException
//     */
//    public function removeProductFromKit($product_id, $kit_id, $rmAllFromKit = false)
//    {
//        $out = new Out();
//
//        $conn = $this->getEntityManager()->getConnection();
//
//        //если надо удалить все издания из комплекта
//        if ($rmAllFromKit) {
//            //если надо удалить все привязки комплекта к изданиям (если в нем несколько изданий)
//            if ($kit_id === 0) {
//                $sql = "DELETE FROM `kits_products` WHERE `product_id` = {$product_id};";
//            }
//            //если надо удалить все привязки издания к комплектам (если он в нескольких комплектах)
//            if ($product_id === 0) {
//                $sql = "DELETE FROM `kits_products` WHERE `kit_id` = {$kit_id};";
//            }
//        } else {
//            $sql = "DELETE FROM `kits_products` WHERE `product_id` = {$product_id} AND `kit_id` = {$kit_id} LIMIT 1;";
//        }
//
//        $stmt = $conn->prepare($sql);
//        try {
//
//            $stmt->execute();
//            $response = true;
//
//        } catch (\Exception $e) {
//
//            if ($e->getErrorCode() > 0) {
//                $response = $out->json([
//                    'status' => "error",
//                    'description' => $e->getMessage(),
//                    'sqlState' => $e->getSQLState(),
//                    'errorCode' => $e->getErrorCode(),
//                    'id' => null
//                ]);
////                var_dump($stmt->fetchAll());
//                var_dump($response);
//            }
//        }
//
//        return $response;
//    }

//    /**
//     * @param $kit_id
//     * @return array
//     * @throws \Doctrine\DBAL\DBALException
//     */
//    public function getProductsByKitId($kit_id)
//    {
//        $out = new Out();
//
//        $conn = $this->getEntityManager()->getConnection();
//
//        $sql = "SELECT products.*
//                FROM products
//                  INNER JOIN kits_products ON products.id = kits_products.product_id
//                  RIGHT JOIN kits ON kits_products.kit_id = kits.id
//                WHERE kits_products.kit_id = {$kit_id}
//                GROUP BY products.name_product
//                ORDER BY products.id
//              ";
//
//        $stmt = $conn->prepare($sql);
//        try {
//
//            $stmt->execute();
//            $response = $stmt->fetchAll();
////            var_dump($response);
//
//        } catch (\Exception $e) {
//
//            if ($e->getErrorCode() > 0) {
//                $response = [
//                    'status' => "error",
//                    'description' => $e->getMessage(),
//                    'sqlState' => $e->getSQLState(),
//                    'errorCode' => $e->getErrorCode(),
//                    'id' => null
//                ];
////                var_dump($stmt->fetchAll());
////                var_dump($response);
//            }
//        }
//
//        return $response;
//    }

//    /**
//     * @param $product_id
//     * @return array
//     * @throws \Doctrine\DBAL\DBALException
//     */
//    public function getKitByProductId($product_id)
//    {
//        $out = new Out();
//
//        $conn = $this->getEntityManager()->getConnection();
//
//        $sql = "SELECT kits.*
//                FROM kits
//                  INNER JOIN kits_products ON kits.id = kits_products.kit_id
//                  RIGHT JOIN products ON kits_products.product_id = products.id
//                WHERE kits_products.product_id = {$product_id}
//                GROUP BY kits.name_kit
//                ORDER BY kits.id;
//              ";
//
//        $stmt = $conn->prepare($sql);
//        try {
//
//            $stmt->execute();
//            $response = $stmt->fetchAll();
////            var_dump($response);
//
//        } catch (\Exception $e) {
//
//            if ($e->getErrorCode() > 0) {
//                $response = [
//                    'status' => "error",
//                    'description' => $e->getMessage(),
//                    'sqlState' => $e->getSQLState(),
//                    'errorCode' => $e->getErrorCode(),
//                    'id' => null
//                ];
////                var_dump($stmt->fetchAll());
////                var_dump($response);
//            }
//        }
//
//        return $response;
//    }

}
