<?php
/**
 * Removes multiple policies
 *
 * @param integer $policies A comma-separated list of policies
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('policy');

if (empty($scriptProperties['policies'])) {
    return $modx->error->failure($modx->lexicon('policy_err_ns'));
}

$policyIds = explode(',',$scriptProperties['policies']);

foreach ($policyIds as $policyId) {
    /* get policy */
    $policy = $modx->getObject('modAccessPolicy',$policyId);
    if ($policy == null) return $modx->error->failure($modx->lexicon('policy_err_nf'));

    /* remove policy */
    if ($policy->remove() == false) {
        return $modx->error->failure($modx->lexicon('policy_err_remove'));
    }

    /* log manager action */
    $modx->logManagerAction('remove_access_policy','modAccessPolicy',$policy->get('id'));
}

return $modx->error->success();
