<?php

use Doctrine\ORM\Mapping\ClassMetadataInfo;

$metadata->setInheritanceType(ClassMetadataInfo::INHERITANCE_TYPE_NONE);
$metadata->customRepositoryClassName = 'Rg\ApiBundle\Repository\ActionsRepository';
$metadata->setChangeTrackingPolicy(ClassMetadataInfo::CHANGETRACKING_DEFERRED_IMPLICIT);
$metadata->mapField(array(
   'fieldName' => 'id',
   'type' => 'integer',
   'id' => true,
   'columnName' => 'id',
  ));
$metadata->mapField(array(
   'columnName' => 'name',
   'fieldName' => 'name',
   'type' => 'string',
   'length' => 255,
  ));
$metadata->mapField(array(
   'columnName' => 'introtext',
   'fieldName' => 'introtext',
   'type' => 'string',
   'length' => 255,
  ));
$metadata->mapField(array(
   'columnName' => 'description',
   'fieldName' => 'description',
   'type' => 'text',
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
   'columnName' => 'discount',
   'fieldName' => 'discount',
   'type' => 'smallint',
  ));
$metadata->mapField(array(
   'columnName' => 'giftDescription',
   'fieldName' => 'giftDescription',
   'type' => 'text',
  ));
$metadata->mapField(array(
   'columnName' => 'flagVisibleOnSite',
   'fieldName' => 'flagVisibleOnSite',
   'type' => 'boolean',
  ));
$metadata->mapField(array(
   'columnName' => 'flagPercentOrFix',
   'fieldName' => 'flagPercentOrFix',
   'type' => 'boolean',
  ));
$metadata->mapField(array(
   'columnName' => 'cntUsed',
   'fieldName' => 'cntUsed',
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
   'type' => 'string',
   'length' => 255,
  ));
$metadata->mapField(array(
   'columnName' => 'promocodeId',
   'fieldName' => 'promocodeId',
   'type' => 'integer',
  ));
$metadata->mapField(array(
   'columnName' => 'userId',
   'fieldName' => 'userId',
   'type' => 'string',
   'length' => 255,
  ));
$metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_AUTO);