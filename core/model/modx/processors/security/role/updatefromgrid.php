<?php
/**
 * Updates a role from a grid. Passed as JSON data
 *
 * @param integer $id The ID of the role
 *
 * @package modx
 * @subpackage processors.security.role
 */
if (!$modx->hasPermission('save_role')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* parse json data */
$_DATA = $modx->fromJSON($scriptProperties['data']);

if (empty($_DATA['id'])) return $modx->error->failure($modx->lexicon('role_err_ns'));
$role = $modx->getObject('modUserGroupRole',$_DATA['id']);
if (empty($role)) return $modx->error->failure($modx->lexicon('role_err_nfs',array('role' => $_DATA['id'])));

/* set role data */
$role->fromArray($_DATA);

/* save role */
if ($role->save() == false) {
    return $modx->error->failure($modx->lexicon('role_err_save'));
}

/* log manager action */
$modx->logManagerAction('role_update','modUserGroupRole',$role->get('id'));

return $modx->error->success();