<?php
/**
 * Update a role from a POST request
 *
 * @param integer $id The ID of the role
 *
 * @package modx
 * @subpackage processors.security.role
 */
$modx->lexicon->load('user');

if (!$modx->hasPermission(array('access_permissions' => true, 'save_role' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

$role = $modx->getObject('modUserGroupRole',$_POST['id']);
if ($role == null) return $modx->error->failure($modx->lexicon('role_err_nfs',array('role' => $_POST['id'])));

if ($_POST['name'] == '') {
	return $modx->error->failure($modx->lexicon('role_err_ns_name'));
}

$role->fromArray($_POST);
if ($role->save() == false) {
	return $modx->error->failure($modx->lexicon('role_err_save'));
}

/* log manager action */
$modx->logManagerAction('role_update','modUserGroupRole',$role->get('id'));

return $modx->error->success();