<?php

use Doctrine\ORM\Mapping\ClassMetadataInfo;

$metadata->setInheritanceType(ClassMetadataInfo::INHERITANCE_TYPE_NONE);
$metadata->customRepositoryClassName = 'Rg\ApiBundle\Repository\ProductsRepository';
$metadata->setChangeTrackingPolicy(ClassMetadataInfo::CHANGETRACKING_DEFERRED_IMPLICIT);
$metadata->mapField(array(
   'fieldName' => 'id',
   'type' => 'integer',
   'id' => true,
   'columnName' => 'id',
  ));
$metadata->mapField(array(
   'columnName' => 'nameProduct',
   'fieldName' => 'nameProduct',
   'type' => 'string',
   'length' => '128',
  ));
$metadata->mapField(array(
   'columnName' => 'frequency',
   'fieldName' => 'frequency',
   'type' => 'smallint',
  ));
$metadata->mapField(array(
   'columnName' => 'flagSubscribe',
   'fieldName' => 'flagSubscribe',
   'type' => 'boolean',
  ));
$metadata->mapField(array(
   'columnName' => 'flagBuy',
   'fieldName' => 'flagBuy',
   'type' => 'boolean',
  ));
$metadata->mapField(array(
   'columnName' => 'postIndex',
   'fieldName' => 'postIndex',
   'type' => 'integer',
  ));
$metadata->mapField(array(
   'columnName' => 'image',
   'fieldName' => 'image',
   'type' => 'string',
  ));
$metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_AUTO);