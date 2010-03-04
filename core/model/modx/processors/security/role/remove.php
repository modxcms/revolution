<?php
/**
 * Removes a role.
 *
 * @param integer $id The ID of the role
 *
 * @package modx
 * @subpackage processors.security.role
 */
if (!$modx->hasPermission('delete_role')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('role_err_nf'));
$role = $modx->getObject('modUserGroupRole',$scriptProperties['id']);
if (empty($role)) return $modx->error->failure($modx->lexicon('role_err_nfs',array('role' => $scriptProperties['id'])));

/* don't delete the Member or Super User roles */
/* TODO: when this is converted in build script, convert to i18n */
if ($role->get('name') == 'Member' || $role->get('name') == 'Super User') {
    return $modx->error->failure($modx->lexicon('role_err_remove_admin'));
}

/* don't delete if this role is assigned */
$cc = $modx->newQuery('modUserGroupMember');
$cc = $cc->where(array('role' => $role->get('id')));
if ($modx->getCount('modUserGroupMember',$cc) > 0) {
    return $modx->error->failure($modx->lexicon('role_err_has_users'));
}

/* remove role */
if ($role->remove() == false) {
    return $modx->error->failure($modx->lexicon('role_err_save'));
}

/* log manager action */
$modx->logManagerAction('role_delete','modUserGroupRole',$role->get('id'));

return $modx->error->success();