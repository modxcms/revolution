<?php
/**
 * Specific upgrades for Revolution 2.2.0-rc1
 *
 * @package setup
 * @subpackage upgrades
 */
/* handle new class creation */
$classes = array(
);
if (!empty($classes)) {
    $this->createTable($classes);
}
unset($classes);

/* add hide_children_in_tree to modResource */
$class = 'modResource';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'hide_children_in_tree','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'hide_children_in_tree'));

$description = $this->install->lexicon('add_index',array('index' => 'hide_children_in_tree','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'hide_children_in_tree'));

/* add show_in_tree to modResource */
$class = 'modResource';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'show_in_tree','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'show_in_tree'));

$description = $this->install->lexicon('add_index',array('index' => 'show_in_tree','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'show_in_tree'));

/* modify type of modTemplateVar.default_text to MEDIUMTEXT */
$class = 'modTemplateVar';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('modify_column', array('column' => 'default_text', 'old' => 'TEXT', 'new' => 'MEDIUMTEXT', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'default_text'));

/* modify type of modTemplateVarResource.value to MEDIUMTEXT */
$class = 'modTemplateVarResource';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('modify_column', array('column' => 'value', 'old' => 'TEXT', 'new' => 'MEDIUMTEXT', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'value'));
