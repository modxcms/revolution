<?php
/**
 * Specific upgrades for Revolution 2.2.10-pl
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */

/* Update modTransportPackage version_* and release_index fields */
$class = 'transport.modTransportPackage';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('modify_column',array('column' => 'version_major', 'old' => 'tinyint', 'new' => 'smallint', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'version_major'));
$description = $this->install->lexicon('modify_column',array('column' => 'version_minor', 'old' => 'tinyint', 'new' => 'smallint', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'version_minor'));
$description = $this->install->lexicon('modify_column',array('column' => 'version_patch', 'old' => 'tinyint', 'new' => 'smallint', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'version_patch'));
$description = $this->install->lexicon('modify_column',array('column' => 'release_index', 'old' => 'tinyint', 'new' => 'smallint', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'release_index'));
