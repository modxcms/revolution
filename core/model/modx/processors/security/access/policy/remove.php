<?php
/**
 * Removes a policy
 *
 * @param integer $id The ID of the policy
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('policy');

/* get policy */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('policy_err_ns'));
$policy = $modx->getObject('modAccessPolicy', $scriptProperties['id']);
if ($policy == null) return $modx->error->failure($modx->lexicon('policy_err_nf'));

/* remove policy */
if ($policy->remove() == false) {
    return $modx->error->failure($modx->lexicon('policy_err_remove'));
}

/* log manager action */
$modx->logManagerAction('remove_access_policy','modAccessPolicy',$policy->get('id'));

return $modx->error->success();