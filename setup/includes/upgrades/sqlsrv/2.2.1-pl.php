<?php
/**
 * Specific upgrades for Revolution 2.2.1-pl
 *
 * @var modX $modx
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

/** Add properties field to modResource */
$class = 'modResource';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'properties','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'properties'));

/* add session_stale field to modUser */
$class = 'modUser';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'session_stale','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'session_stale'));

/* modify nullability and add index to modSession.access */
$class = 'modSession';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('modify_column',array('column' => 'access','table' => $table, 'old' => 'NULL', 'new' => 'NOT NULL'));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'access'));

$description = $this->install->lexicon('add_index',array('index' => 'access','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'access'));

/* add sudo field to modUser */
$class = 'modUser';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'sudo','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'sudo'));

/* get all users who are Super Users of Administrator group and make them sudo */
$c = $modx->newQuery('modUser');
$c->innerJoin('modUserGroupMember','UserGroupMembers');
$c->innerJoin('modUserGroup','UserGroup',array(
    'UserGroup.id = UserGroupMembers.user_group',
    'UserGroup.name' => 'Administrator',
));
$c->innerJoin('modUserGroupRole','Role',array(
    'Role.id = UserGroupMembers.role',
    'Role.name' => 'Super User',
));
$users = $modx->getCollection('modUser',$c);
/** @var modUser $user */
foreach ($users as $user) {
    $user->set('sudo',true);
    $user->save();
}

/* drop modAction parent field */
$class = 'modAction';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('drop_index',array('index' => 'parent','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'removeIndex'), array($class, 'parent'));
$description = $this->install->lexicon('drop_column',array('column' => 'parent','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'removeField'), array($class, 'parent'));

/* add assets_path field to modNamespace */
$class = 'modNamespace';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'assets_path','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'assets_path'));