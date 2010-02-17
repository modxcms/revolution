<?php
/**
 * Update a role from a POST request
 *
 * @param integer $id The ID of the role
 *
 * @package modx
 * @subpackage processors.security.role
 */
if (!$modx->hasPermission('save_role')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* get role */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('role_err_ns'));
$role = $modx->getObject('modUserGroupRole',$_POST['id']);
if ($role == null) return $modx->error->failure($modx->lexicon('role_err_nfs',array('role' => $_POST['id'])));

/* do validation */
if (empty($_POST['name'])) $modx->error->addError('name',$modx->lexicon('role_err_ns_name'));

if ($modx->error->hasError()) return $modx->error->failure();

/* set and save role */
$role->fromArray($_POST);
if ($role->save() == false) {
	return $modx->error->failure($modx->lexicon('role_err_save'));
}

/* log manager action */
$modx->logManagerAction('role_update','modUserGroupRole',$role->get('id'));

return $modx->error->success();