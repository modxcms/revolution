<?php
/**
 * Common upgrade script for 2.7 for adding editedon column to elements tables.
 *
 * @var modX $modx
 * @package setup
 */

/* add modChunk.editedon field */
$class = 'modChunk';
$table = $modx->getTableName($class);

$description = $this->install->lexicon('add_column', array('column' => 'editedon', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'editedon'));

/* add modPlugin.editedon field */
$class = 'modPlugin';
$table = $modx->getTableName($class);

$description = $this->install->lexicon('add_column', array('column' => 'editedon', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'editedon'));

/* add modSnippet.editedon field */
$class = 'modSnippet';
$table = $modx->getTableName($class);

$description = $this->install->lexicon('add_column', array('column' => 'editedon', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'editedon'));

/* add modTemplate.editedon field */
$class = 'modTemplate';
$table = $modx->getTableName($class);

$description = $this->install->lexicon('add_column', array('column' => 'editedon', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'editedon'));

/* add modTemplateVar.editedon field */
$class = 'modTemplateVar';
$table = $modx->getTableName($class);

$description = $this->install->lexicon('add_column', array('column' => 'editedon', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'editedon'));
