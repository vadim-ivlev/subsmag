<?php

use Doctrine\ORM\Mapping\ClassMetadataInfo;

$metadata->setInheritanceType(ClassMetadataInfo::INHERITANCE_TYPE_NONE);
$metadata->customRepositoryClassName = 'Rg\ApiBundle\Repository\PeriodsRepository';
$metadata->setChangeTrackingPolicy(ClassMetadataInfo::CHANGETRACKING_DEFERRED_IMPLICIT);
$metadata->mapField(array(
   'fieldName' => 'id',
   'type' => 'integer',
   'id' => true,
   'columnName' => 'id',
  ));
$metadata->mapField(array(
   'columnName' => 'monthStart',
   'fieldName' => 'monthStart',
   'type' => 'smallint',
  ));
$metadata->mapField(array(
   'columnName' => 'yearStart',
   'fieldName' => 'yearStart',
   'type' => 'smallint',
  ));
$metadata->mapField(array(
   'columnName' => 'periodMonths',
   'fieldName' => 'periodMonths',
   'type' => 'smallint',
  ));
$metadata->mapField(array(
   'columnName' => 'quantityMonthsStart',
   'fieldName' => 'quantityMonthsStart',
   'type' => 'smallint',
  ));
$metadata->mapField(array(
   'columnName' => 'quantityMonthsEnd',
   'fieldName' => 'quantityMonthsEnd',
   'type' => 'smallint',
  ));
$metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_AUTO);