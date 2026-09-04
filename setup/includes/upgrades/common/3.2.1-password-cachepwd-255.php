<?php

/**
 * Ensure modUser.password and modUser.cachepwd support 255 chars (PHP recommendation for future hash algorithms).
 * Idempotent: safe to run on 2.7→3.2.1 upgrades where 2.7-native-password-hash may not have run.
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modUser;

$class = modUser::class;
$table = $modx->getTableName($class);

$password = $this->install->lexicon('alter_column', ['column' => 'password', 'table' => $table]);
$this->processResults($class, $password, [$modx->manager, 'alterField'], [$class, 'password']);

$cachepwd = $this->install->lexicon('alter_column', ['column' => 'cachepwd', 'table' => $table]);
$this->processResults($class, $cachepwd, [$modx->manager, 'alterField'], [$class, 'cachepwd']);
