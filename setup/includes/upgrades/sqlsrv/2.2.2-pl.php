<?php
/**
 * Specific upgrades for Revolution 2.2.2-pl
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

/* [#7610] User.sudo field invalid for sqlsrv */
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

/* [#7646] Increase size of session.id field to handle up to 255 chars */
$class = 'modSession';
$table = $modx->getTableName('modSession');
$description = $this->install->lexicon('modify_column',array('column' => 'id', 'old' => 'nvarchar(40)', 'new' => 'nvarchar(255)', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'id'));
