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

/* assign primary_group for users with one group, or with admin group */
/** @todo Fix this, as it's way way too slow as-is and causes out-of-mem errors */
/*
$c = $modx->newQuery('modUser');
$c->where(array(
    'primary_group' => 0,
));
$users = $modx->getCollectionGraph('modUser','{"UserGroupMembers":{"UserGroup":{}}}',$c);
// @var modUser $user
foreach ($users as $user) {
    if (count($user->UserGroupMembers) == 1) {
        // @var modUserGroupMember $membership 
        foreach ($user->UserGroupMembers as $membership) {
            $user->set('primary_group',$membership->get('user_group'));
            $user->save();
            break;
        }
    } elseif (count($user->UserGroupMembers) > 0) {
        $idx = 0;
        // @var modUserGroupMember $membership 
        foreach ($user->UserGroupMembers as $membership) {
            if ($membership->UserGroup->get('name') == 'Administrator' || $idx == 0) {
                $user->set('primary_group',$membership->get('user_group'));
                $user->save();
                if ($membership->get('name') == 'Administrator') {
                    break;
                }
            }
            $idx++;
        }
    }
}
*/

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