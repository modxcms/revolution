<?php
/**
 * Updates a role from a grid. Passed as JSON data
 *
 * @param integer $id The ID of the role
 *
 * @package modx
 * @subpackage processors.security.role
 */
if (!$modx->hasPermission(array('access_permissions' => true, 'save_role' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$modx->lexicon->load('user');

/* parse json data */
$_DATA = $modx->fromJSON($_POST['data']);

$role = $modx->getObject('modUserGroupRole',$_DATA['id']);
if ($role == null) {
    return $modx->error->failure($modx->lexicon('role_err_nfs',array('role' => $_DATA['id'])));
}

/* set role data */
$role->fromArray($_DATA);

/* save role */
if ($role->save() == false) {
    return $modx->error->failure($modx->lexicon('role_err_save'));
}

/* log manager action */
$modx->logManagerAction('role_update','modUserGroupRole',$role->get('id'));

return $modx->error->success();