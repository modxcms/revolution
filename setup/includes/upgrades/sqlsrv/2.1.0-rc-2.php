<?php
/**
 * Specific upgrades for Revolution 2.1.0-rc-2
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

/* add missing description field from rc1 to modUserGroup */
$class = 'modUserGroup';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'description','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'description'));

/* add rank to modUserGroup */
$class = 'modUserGroup';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'rank','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'rank'));

$description = $this->install->lexicon('add_index',array('index' => 'rank','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'rank'));

/* add rank to modUserGroupMember */
$class = 'modUserGroupMember';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'rank','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'rank'));

$description = $this->install->lexicon('add_index',array('index' => 'rank','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'rank'));

/* change help screen URL references */
$setting = $modx->getObject('modSystemSetting',array('key' => 'base_help_url'));
if ($setting && $setting->get('value') == 'http://rtfm.modx.com/display/revolution21/') {
    $setting->set('value','http://rtfm.modx.com/display/revolution20/');
    $setting->save();
}