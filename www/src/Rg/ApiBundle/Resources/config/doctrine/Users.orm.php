<?php

use Doctrine\ORM\Mapping\ClassMetadataInfo;

$metadata->setInheritanceType(ClassMetadataInfo::INHERITANCE_TYPE_NONE);
$metadata->customRepositoryClassName = 'Rg\ApiBundle\Repository\UsersRepository';
$metadata->setChangeTrackingPolicy(ClassMetadataInfo::CHANGETRACKING_DEFERRED_IMPLICIT);
$metadata->mapField(array(
   'fieldName' => 'id',
   'type' => 'integer',
   'id' => true,
   'columnName' => 'id',
  ));
$metadata->mapField(array(
   'columnName' => 'login',
   'fieldName' => 'login',
   'type' => 'string',
   'length' => '128',
  ));
$metadata->mapField(array(
   'columnName' => 'password',
   'fieldName' => 'password',
   'type' => 'string',
   'length' => 255,
  ));
$metadata->mapField(array(
   'columnName' => 'userKey',
   'fieldName' => 'userKey',
   'type' => 'string',
   'length' => 255,
  ));
$metadata->mapField(array(
   'columnName' => 'dateRegistration',
   'fieldName' => 'dateRegistration',
   'type' => 'datetime',
  ));
$metadata->mapField(array(
   'columnName' => 'dateLastlogin',
   'fieldName' => 'dateLastlogin',
   'type' => 'datetime',
  ));
$metadata->mapField(array(
   'columnName' => 'flagCanRest',
   'fieldName' => 'flagCanRest',
   'type' => 'boolean',
  ));
$metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_AUTO);