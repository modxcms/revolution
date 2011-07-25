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

/* add primary_group field and index to modUser */
$class = 'modUser';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'primary_group','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'primary_group'));
$description = $this->install->lexicon('add_index',array('index' => 'primary_group','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'primary_group'));

/* assign primary_group for users with one group, or with admin group */
$users = $modx->getIterator('modUser',array(
    'primary_group' => 0,
));
/** @var modUser $user */
foreach ($users as $user) {
    $c = $modx->newQuery('modUserGroupMember');
    $c->innerJoin('modUserGroup','UserGroup');
    $c->select($modx->getSelectColumns('modUserGroupMember','modUserGroupMember'));
    $c->select(array('UserGroup.name'));
    $c->where(array(
        'modUserGroupMember.member' => $user->get('id'),
    ));
    $c->sortby($modx->getSelectColumns('modUserGroupMember','modUserGroupMember','',array('user_group')),'ASC');
    $memberships = $modx->getCollection('modUserGroupMember',$c);
    if (count($memberships) == 1) {
        /** @var modUserGroupMember $membership */
        foreach ($memberships as $membership) {
            $user->set('primary_group',$membership->get('user_group'));
            $user->save();
            break;
        }
    } elseif (count($memberships) > 0) {
        $idx = 0;
        /** @var modUserGroupMember $membership */
        foreach ($memberships as $membership) {
            if ($membership->get('name') == 'Administrator' || $idx == 0) {
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