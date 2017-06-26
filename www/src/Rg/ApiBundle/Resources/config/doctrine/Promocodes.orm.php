<?php

use Doctrine\ORM\Mapping\ClassMetadataInfo;

$metadata->setInheritanceType(ClassMetadataInfo::INHERITANCE_TYPE_NONE);
$metadata->customRepositoryClassName = 'Rg\ApiBundle\Repository\PromocodesRepository';
$metadata->setChangeTrackingPolicy(ClassMetadataInfo::CHANGETRACKING_DEFERRED_IMPLICIT);
$metadata->mapField(array(
   'fieldName' => 'id',
   'type' => 'integer',
   'id' => true,
   'columnName' => 'id',
  ));
$metadata->mapField(array(
   'columnName' => 'code',
   'fieldName' => 'code',
   'type' => 'string',
   'length' => '10',
  ));
$metadata->mapField(array(
   'columnName' => 'dateStart',
   'fieldName' => 'dateStart',
   'type' => 'datetime',
  ));
$metadata->mapField(array(
   'columnName' => 'dateEnd',
   'fieldName' => 'dateEnd',
   'type' => 'datetime',
  ));
$metadata->mapField(array(
   'columnName' => 'flagUsed',
   'fieldName' => 'flagUsed',
   'type' => 'boolean',
  ));
$metadata->mapField(array(
   'columnName' => 'actionId',
   'fieldName' => 'actionId',
   'type' => 'integer',
  ));
$metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_AUTO); 