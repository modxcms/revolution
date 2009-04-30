<?php
/**
 * Removes a policy
 *
 * @param integer $id The ID of the policy
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
$modx->lexicon->load('policy');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['id'])) {
    return $modx->error->failure('Policy id not specified!');
}
$id = $_REQUEST['id'];

$policy = $modx->getObject('modAccessPolicy', $id);
if ($policy === null) {
    return $modx->error->failure("Could not find specified object with id {$id}!");
} else {
    if (!$policy->remove()) {
        return $modx->error->failure("Error removing object with id {$id}!");
    }
}

/* log manager action */
$modx->logManagerAction('save_access_policy','modAccessPolicy',$policy->get('id'));

return $modx->error->success();