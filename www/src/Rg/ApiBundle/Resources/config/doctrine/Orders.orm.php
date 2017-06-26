<?php

use Doctrine\ORM\Mapping\ClassMetadataInfo;

$metadata->setInheritanceType(ClassMetadataInfo::INHERITANCE_TYPE_NONE);
$metadata->customRepositoryClassName = 'Rg\ApiBundle\Repository\OrdersRepository';
$metadata->setChangeTrackingPolicy(ClassMetadataInfo::CHANGETRACKING_DEFERRED_IMPLICIT);
$metadata->mapField(array(
   'fieldName' => 'id',
   'type' => 'integer',
   'id' => true,
   'columnName' => 'id',
  ));
$metadata->mapField(array(
   'columnName' => 'userId',
   'fieldName' => 'userId',
   'type' => 'integer',
  ));
$metadata->mapField(array(
   'columnName' => 'productId',
   'fieldName' => 'productId',
   'type' => 'integer',
  ));
$metadata->mapField(array(
   'columnName' => 'kitId',
   'fieldName' => 'kitId',
   'type' => 'integer',
  ));
$metadata->mapField(array(
   'columnName' => 'zoneId',
   'fieldName' => 'zoneId',
   'type' => 'integer',
  ));
$metadata->mapField(array(
   'columnName' => 'subscribeId',
   'fieldName' => 'subscribeId',
   'type' => 'integer',
  ));
$metadata->mapField(array(
   'columnName' => 'date',
   'fieldName' => 'date',
   'type' => 'datetime',
  ));
$metadata->mapField(array(
   'columnName' => 'address',
   'fieldName' => 'address',
   'type' => 'string',
   'length' => 255,
  ));
$metadata->mapField(array(
   'columnName' => 'price',
   'fieldName' => 'price',
   'type' => 'float',
  ));
$metadata->mapField(array(
   'columnName' => 'flagPaid',
   'fieldName' => 'flagPaid',
   'type' => 'boolean',
  ));
$metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_AUTO);