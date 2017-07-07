<?php
/**
 * Created by PhpStorm.
 * User: merinov
 * Date: 21.06.17
 * Time: 9:23
 */

namespace Rg\ApiBundle\Repository;

use Rg\ApiBundle\Controller\Outer as Out;
use \Doctrine\ORM\EntityRepository;


/*
 * Класс с методами для реализации Много-ко-многим
 * */

/**
 * Class Relations
 * @package Rg\ApiBundle\Repository
 */
class Relations extends EntityRepository
{

    /**
     * Добавленние связи
     * @param $entity_id - ид элемента сущности 1 в связи
     * @param $action_id - ид элемента сущности 2 в связи
     * @param $rel_entity_name - В единственном числе! - название сущности
     * @param $target_entity_name - В единственном числе! - название сущности
     * @return bool|\Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\DBAL\DBALException
     */
    public function addRelationToEntity($entity_id, $action_id, $rel_entity_name, $target_entity_name)
    {
        $out = new Out();

        $conn = $this->getEntityManager()->getConnection();

        $sql = "INSERT INTO `{$target_entity_name}s_{$rel_entity_name}s` (`{$target_entity_name}_id`, `{$rel_entity_name}_id`) VALUES ({$action_id}, {$entity_id});";

        $stmt = $conn->prepare($sql);
        try {

            $stmt->execute();
            $response = true;

        } catch (\Exception $e) {

            if ($e->getErrorCode() > 0) {
                $response = $out->json([
                    'status' => "error",
                    'entity' => $rel_entity_name,
                    'description' => $e->getMessage(),
                    'sqlState' => $e->getSQLState(),
                    'errorCode' => $e->getErrorCode(),
                    'id' => null
                ]);
//                var_dump($stmt->fetchAll());
//                var_dump($response);
            }
        }

        return $response;
    }


    /**
     * Получение связи по ид элемента сущности
     * @param $action_id - ид элемента сущности, по которому хотим выбрать связи
     * @param $rel_entity_name - В единственном числе! - название сущности
     * @param $target_entity_name - В единственном числе! - название сущности
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getRelationByEntityId($action_id, $rel_entity_name, $target_entity_name)
    {
        $out = new Out();

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT {$rel_entity_name}s.*
                FROM {$rel_entity_name}s
                  INNER JOIN {$target_entity_name}s_{$rel_entity_name}s ON {$rel_entity_name}s.id = {$target_entity_name}s_{$rel_entity_name}s.{$rel_entity_name}_id
                  RIGHT JOIN {$target_entity_name}s ON {$target_entity_name}s_{$rel_entity_name}s.{$target_entity_name}_id = {$target_entity_name}s.id
                WHERE {$target_entity_name}s_{$rel_entity_name}s.{$target_entity_name}_id = {$action_id}
                GROUP BY {$rel_entity_name}s.name_{$rel_entity_name}
                ORDER BY {$rel_entity_name}s.id
              ";

        $stmt = $conn->prepare($sql);
        try {

            $stmt->execute();
            $response = $stmt->fetchAll();
//            var_dump($response);

        } catch (\Exception $e) {

            if ($e->getErrorCode() > 0) {
                $response = [
                    'status' => "error",
                    'description' => $e->getMessage(),
                    'sqlState' => $e->getSQLState(),
                    'errorCode' => $e->getErrorCode(),
                    'id' => null
                ];
//                var_dump($stmt->fetchAll());
//                var_dump($response);
            }
        }

        return $response;
    }


    /**
     * Удаление свзей. Вызывается при обновлении, в том числе.
     * @param $entity_id
     * @param $action_id
     * @param $rel_entity_name - В единственном числе!
     * @param $target_entity_name - В единственном числе!
     * @param bool $rmAllFromAction - флаг-защита для того, чтобы случайно не удалить все связи сущностей
     * @return bool|\Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\DBAL\DBALException
     */
    public function removeRelationFromEntity($entity_id, $action_id, $rel_entity_name, $target_entity_name, $rmAllFromAction = false)
    {
        $out = new Out();


        $conn = $this->getEntityManager()->getConnection();

        //если надо удалить все издания из комплекта
        if ($rmAllFromAction) {
            //если надо удалить все привязки комплекта к изданиям (если в нем несколько изданий)
            if ($action_id === 0) {
                $sql = "DELETE FROM `{$target_entity_name}s_{$rel_entity_name}s` WHERE `{$rel_entity_name}_id` = {$entity_id};";
            }
            //если надо удалить все привязки издания к комплектам (если он в нескольких комплектах)
            if ($entity_id === 0) {
                $sql = "DELETE FROM `{$target_entity_name}s_{$rel_entity_name}s` WHERE `{$target_entity_name}_id` = {$action_id};";
            }
        } else {
            $sql = "DELETE FROM `{$target_entity_name}s_{$rel_entity_name}s` WHERE `{$rel_entity_name}_id` = {$entity_id} AND `{$target_entity_name}_id` = {$action_id} LIMIT 1;";
        }

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
}