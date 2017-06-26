<?php

use Doctrine\ORM\Mapping\ClassMetadataInfo;

$metadata->setInheritanceType(ClassMetadataInfo::INHERITANCE_TYPE_NONE);
$metadata->customRepositoryClassName = 'Rg\ApiBundle\Repository\SubscribesRepository';
$metadata->setChangeTrackingPolicy(ClassMetadataInfo::CHANGETRACKING_DEFERRED_IMPLICIT);
$metadata->mapField(array(
   'fieldName' => 'id',
   'type' => 'integer',
   'id' => true,
   'columnName' => 'id',
  ));
$metadata->mapField(array(
   'columnName' => 'namePeriodId',
   'fieldName' => 'namePeriodId',
   'type' => 'integer',
  ));
$metadata->mapField(array(
   'columnName' => 'areaId',
   'fieldName' => 'areaId',
   'type' => 'integer',
  ));
$metadata->mapField(array(
   'columnName' => 'subscribePeriodStart',
   'fieldName' => 'subscribePeriodStart',
   'type' => 'datetime',
  ));
$metadata->mapField(array(
   'columnName' => 'subscribePeriodEnd',
   'fieldName' => 'subscribePeriodEnd',
   'type' => 'datetime',
  ));
$metadata->mapField(array(
   'columnName' => 'periodId',
   'fieldName' => 'periodId',
   'type' => 'integer',
  ));
$metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_AUTO);