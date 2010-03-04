<?php
/**
 * Creates a role from a POST request.
 *
 * @package modx
 * @subpackage processors.security.role
 */
if (!$modx->hasPermission('new_role')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* validate form */
if (empty($scriptProperties['name'])) $modx->error->addError('name',$modx->lexicon('role_err_ns_name'));

$ae = $modx->getObject('modUserGroupRole',array(
    'name' => $scriptProperties['name'],
));
if ($ae != null) $modx->error->addError('name',$modx->lexicon('role_err_ae'));

if ($modx->error->hasError()) return $modx->error->failure();

/* create and save role */
$role = $modx->newObject('modUserGroupRole');
$role->fromArray($scriptProperties);

if ($role->save() == false) {
	return $modx->error->failure($modx->lexicon('role_err_save'));
}

/* log manager action */
$modx->logManagerAction('role_create','modUserGroupRole',$role->get('id'));

return $modx->error->success();