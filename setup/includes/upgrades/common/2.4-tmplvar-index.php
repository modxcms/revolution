<?php
/**
 * New Index for modAccessResourceGroup
 *
 * @var modX $modx
 * @package setup
 */

/* add modAccessResourceGroup.principal_class index */
$class = 'modTemplateVarResourceGroup';
$table = $modx->getTableName($class);

$description = $this->install->lexicon('add_index',array('index' => 'tmplvar_template','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'tmplvar_template'));
