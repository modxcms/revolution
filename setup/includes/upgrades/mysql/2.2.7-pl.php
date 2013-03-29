<?php
/**
 * Specific upgrades for Revolution 2.2.7-pl
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */

/* Add tv_cnt index to modTemplateVarResource table */
$class = 'modTemplateVarResource';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_index',array('index' => 'tv_cnt', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'tv_cnt'));
