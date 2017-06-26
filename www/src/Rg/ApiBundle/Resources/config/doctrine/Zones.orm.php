<?php

use Doctrine\ORM\Mapping\ClassMetadataInfo;

$metadata->setInheritanceType(ClassMetadataInfo::INHERITANCE_TYPE_NONE);
$metadata->customRepositoryClassName = 'Rg\ApiBundle\Repository\ZonesRepository';
$metadata->setChangeTrackingPolicy(ClassMetadataInfo::CHANGETRACKING_DEFERRED_IMPLICIT);
$metadata->mapField(array(
   'fieldName' => 'id',
   'type' => 'integer',
   'id' => true,
   'columnName' => 'id',
  ));
$metadata->mapField(array(
   'columnName' => 'zoneNumber',
   'fieldName' => 'zoneNumber',
   'type' => 'smallint',
  ));
$metadata->mapField(array(
   'columnName' => 'areaId',
   'fieldName' => 'areaId',
   'type' => 'smallint',
  ));
$metadata->mapField(array(
   'columnName' => 'tarifId',
   'fieldName' => 'tarifId',
   'type' => 'integer',
  ));
$metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_AUTO);