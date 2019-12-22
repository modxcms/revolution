<?php
/**
 * Common upgrade script to update modUser fields for native password hashing feature.
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modUser;

$class = modUser::class;
$table = $modx->getTableName($class);

/* modify modUser.password field */
$password = $this->install->lexicon('alter_column', ['column' => 'password','table' => $table]);
$this->processResults($class, $password, [$modx->manager, 'alterField'], [$class, 'password']);

/* modify modUser.cachepwd field */
$cachepwd = $this->install->lexicon('alter_column', ['column' => 'cachepwd','table' => $table]);
$this->processResults($class, $cachepwd, [$modx->manager, 'alterField'], [$class, 'cachepwd']);

/* modify modUser.hash_class field */
$hash_class = $this->install->lexicon('alter_column', ['column' => 'hash_class','table' => $table]);
$this->processResults($class, $hash_class, [$modx->manager, 'alterField'], [$class, 'hash_class']);
