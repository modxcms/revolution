<?php
/**
 * Creates a role from a POST request.
 *
 * @package modx
 * @subpackage processors.security.role
 */
$modx->lexicon->load('user');

if (!$modx->hasPermission(array('access_permissions' => true, 'new_role' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

$role = $modx->newObject('modUserGroupRole');

if ($_POST['name'] == '') {
	return $modx->error->failure($modx->lexicon('role_err_ns_name'));
}

$role->fromArray($_POST);
if ($role->save() == false) {
	return $modx->error->failure($modx->lexicon('role_err_save'));
}

/* log manager action */
$modx->logManagerAction('role_create','modUserGroupRole',$role->get('id'));

return $modx->error->success();