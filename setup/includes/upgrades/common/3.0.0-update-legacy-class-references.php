<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


/**
 * @property modX $modx
 */

use MODX\Revolution\Hashing\modMD5;
use MODX\Revolution\Hashing\modNative;
use MODX\Revolution\Hashing\modPBKDF2;
use MODX\Revolution\modAccess;
use MODX\Revolution\modDocument;
use MODX\Revolution\modResource;
use MODX\Revolution\modStaticResource;
use MODX\Revolution\modSymLink;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modWebLink;
use MODX\Revolution\Sources\modFileMediaSource;
use MODX\Revolution\Sources\modFTPMediaSource;
use MODX\Revolution\Sources\modMediaSource;
use MODX\Revolution\Sources\modS3MediaSource;

/* modify default values and value references to core classes in modAccess.principal_class columns */
$accessClasses = $modx->getDescendants(modAccess::class);
foreach ($accessClasses as $class) {
    $table = $modx->getTableName($class);

    $principalClass = $this->install->lexicon('alter_column', ['column' => 'principal_class', 'table' => $table]);
    $this->processResults($class, $principalClass, [$modx->manager, 'alterField'], [$class, 'principal_class']);

    $modx->updateCollection($class, ['principal_class' => modUserGroup::class], ['principal_class' => 'modUserGroup']);
}

/* modify default value and core class references in modResource.class_key column */
$class = modResource::class;
$table = $modx->getTableName($class);

$classKey = $this->install->lexicon('alter_column', ['column' => 'class_key', 'table' => $table]);
$this->processResults($class, $classKey, [$modx->manager, 'alterField'], [$class, 'class_key']);

$modx->updateCollection($class, ['class_key' => modDocument::class], ['class_key' => 'modDocument']);
$modx->updateCollection($class, ['class_key' => modStaticResource::class], ['class_key' => 'modStaticResource']);
$modx->updateCollection($class, ['class_key' => modWebLink::class], ['class_key' => 'modWebLink']);
$modx->updateCollection($class, ['class_key' => modSymLink::class], ['class_key' => 'modSymLink']);

/* modify default values and core class references in modUser class_key and hashing_class columns */
$class = modUser::class;
$table = $modx->getTableName($class);

$classKey = $this->install->lexicon('alter_column', ['column' => 'class_key', 'table' => $table]);
$this->processResults($class, $classKey, [$modx->manager, 'alterField'], [$class, 'class_key']);

$modx->updateCollection($class, ['class_key' => modUser::class], ['class_key' => 'modUser']);

$hashingClass = $this->install->lexicon('alter_column', ['column' => 'hashing_class', 'table' => $table]);
$this->processResults($class, $hashingClass, [$modx->manager, 'alterField'], [$class, 'hashing_class']);

$modx->updateCollection($class, ['hashing_class' => modNative::class], ['hashing_class' => 'hashing.modNative']);
$modx->updateCollection($class, ['hashing_class' => modMD5::class], ['hashing_class' => 'hashing.modMD5']);
$modx->updateCollection($class, ['hashing_class' => modPBKDF2::class], ['hashing_class' => 'hashing.modPBKDF2']);

/* modify default values and core class references in modMediaSource.class_key column */
$class = modMediaSource::class;
$table = $modx->getTableName($class);

$classKey = $this->install->lexicon('alter_column', ['column' => 'class_key', 'table' => $table]);
$this->processResults($class, $classKey, [$modx->manager, 'alterField'], [$class, 'class_key']);

$modx->updateCollection($class, ['class_key' => modFileMediaSource::class], ['class_key' => 'sources.modFileMediaSource']);
$modx->updateCollection($class, ['class_key' => modFTPMediaSource::class], ['class_key' => 'sources.modFTPMediaSource']);
$modx->updateCollection($class, ['class_key' => modS3MediaSource::class], ['class_key' => 'sources.modS3MediaSource']);
