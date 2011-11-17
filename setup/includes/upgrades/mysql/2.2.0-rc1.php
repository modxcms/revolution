<?php
/**
 * Specific upgrades for Revolution 2.2.0-rc1
 *
 * @package setup
 * @subpackage upgrades
 */
/* handle new class creation */
$classes = array(
    'modDashboard',
    'modDashboardWidget',
    'modDashboardWidgetPlacement',
    'sources.modAccessMediaSource',
    'sources.modMediaSource',
    'sources.modMediaSourceElement',
    'sources.modMediaSourceContext',
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

/* add primary_group field and index to modUser */
$class = 'modUser';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'primary_group','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'primary_group'));
$description = $this->install->lexicon('add_index',array('index' => 'primary_group','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'primary_group'));

/* assign primary_group for users in at least one group based on group and member rank */
$tableUserGroups = $modx->getTableName('modUserGroup');
$tableMemberGroups = $modx->getTableName('modUserGroupMember');
$sql = "UPDATE {$table}
SET `primary_group` = (SELECT `user_group` FROM {$tableMemberGroups} `memb`, {$tableUserGroups} `groups` WHERE `memb`.`user_group` = `groups`.`id` AND `memb`.`member` = {$table}.`id` ORDER BY `memb`.`rank` ASC, `groups`.`rank` ASC, `groups`.`id` ASC LIMIT 1)
WHERE `primary_group` = 0
AND EXISTS (SELECT 1 FROM {$tableMemberGroups} WHERE {$tableMemberGroups}.`member` = {$table}.id)";
$description = $this->install->lexicon('update_table_column_data',array('table' => $table, 'column' => 'primary_group', 'class' => $class));
$this->processResults($class, $description, $sql);

/* add dashboard field/index to modUserGroup */
$class = 'modUserGroup';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'dashboard','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'dashboard'));
$description = $this->install->lexicon('add_index',array('index' => 'dashboard','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'dashboard'));

/* add rank to modContext */
$class = 'modContext';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'rank','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'rank'));
$description = $this->install->lexicon('add_index',array('index' => 'rank','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'rank'));

/* media sources upgrades */
include dirname(dirname(__FILE__)).'/common/2.2-media-sources.php';

/* add static field and index to all modElement derivatives */
$class = 'modChunk';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'static','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'static'));
$description = $this->install->lexicon('add_index',array('index' => 'static','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'static'));$class = 'modChunk';
$description = $this->install->lexicon('add_column',array('column' => 'static_file','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'static_file'));
$description = $this->install->lexicon('add_column',array('column' => 'source','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'source'));
$description = $this->install->lexicon('add_column',array('column' => 'property_preprocess','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'property_preprocess'));

$class = 'modPlugin';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'static','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'static'));
$description = $this->install->lexicon('add_index',array('index' => 'static','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'static'));
$description = $this->install->lexicon('add_column',array('column' => 'static_file','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'static_file'));
$description = $this->install->lexicon('add_column',array('column' => 'source','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'source'));
$description = $this->install->lexicon('add_column',array('column' => 'property_preprocess','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'property_preprocess'));

$class = 'modSnippet';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'static','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'static'));
$description = $this->install->lexicon('add_index',array('index' => 'static','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'static'));
$description = $this->install->lexicon('add_column',array('column' => 'static_file','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'static_file'));
$description = $this->install->lexicon('add_column',array('column' => 'source','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'source'));
$description = $this->install->lexicon('add_column',array('column' => 'property_preprocess','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'property_preprocess'));

$class = 'modTemplate';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'static','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'static'));
$description = $this->install->lexicon('add_index',array('index' => 'static','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'static'));
$description = $this->install->lexicon('add_column',array('column' => 'static_file','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'static_file'));
$description = $this->install->lexicon('add_column',array('column' => 'source','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'source'));
$description = $this->install->lexicon('add_column',array('column' => 'property_preprocess','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'property_preprocess'));

$class = 'modTemplateVar';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'static','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'static'));
$description = $this->install->lexicon('add_index',array('index' => 'static','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'static'));
$description = $this->install->lexicon('add_column',array('column' => 'static_file','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'static_file'));
$description = $this->install->lexicon('add_column',array('column' => 'source','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'source'));
$description = $this->install->lexicon('add_column',array('column' => 'property_preprocess','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'property_preprocess'));

/* add is_stream to sources.modMediaSource */
$class = 'sources.modMediaSource';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'is_stream','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'is_stream'));

$description = $this->install->lexicon('add_index',array('index' => 'is_stream','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'is_stream'));

$s3sources = $modx->getCollection('sources.modMediaSource',array(
    'class_key' => 'sources.modS3MediaSource',
));
/** @var modS3MediaSource $s3source */
foreach ($s3sources as $s3source) {
    $s3source->set('is_stream',false);
    $s3source->save();
}

/** Add hide_trees field+index to modDashboard */
$class = 'modDashboard';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'hide_trees','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'hide_trees'));

$description = $this->install->lexicon('add_index',array('index' => 'hide_trees','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'hide_trees'));

/* change help/welcome screen URL references */
/** @var modSystemSetting $setting */
$setting = $modx->getObject('modSystemSetting',array('key' => 'welcome_screen_url'));
if ($setting && in_array($setting->get('value'),array('http://misc.modx.com/revolution/welcome.21.html','http://misc.modx.com/revolution/welcome.20.html'))) {
    $setting->set('value','http://misc.modx.com/revolution/welcome.22.html');
    $setting->save();
}