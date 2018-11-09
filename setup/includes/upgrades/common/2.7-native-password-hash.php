<?php
/**
 * Common upgrade script to update modUser fields for native password hashing feature.
 *
 * @var modX $modx
 * @package setup
 */

$class = 'modUser';
$table = $modx->getTableName($class);

/* modify modUser.password field */
$password = $this->install->lexicon('alter_column',array('column' => 'password','table' => $table));
$this->processResults($class, $password, array($modx->manager, 'alterField'), array($class, 'password'));

/* modify modUser.cachepwd field */
$cachepwd = $this->install->lexicon('alter_column',array('column' => 'cachepwd','table' => $table));
$this->processResults($class, $cachepwd, array($modx->manager, 'alterField'), array($class, 'cachepwd'));

/* modify modUser.hash_class field */
$hash_class = $this->install->lexicon('alter_column',array('column' => 'hash_class','table' => $table));
$this->processResults($class, $hash_class, array($modx->manager, 'alterField'), array($class, 'hash_class'));
