<?php
/**
 * Common upgrade script for 2.4 modTransportProvider
 *
 * @var modX $modx
 * @package setup
 */

/* add modTransportProvider.name field */
$class = 'transport.modTransportProvider';
$table = $modx->getTableName($class);

$description = $this->install->lexicon('add_column',array('column' => 'active','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'active'));

$description = $this->install->lexicon('add_column',array('column' => 'priority','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'priority'));

$description = $this->install->lexicon('add_column',array('column' => 'properties','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'properties'));