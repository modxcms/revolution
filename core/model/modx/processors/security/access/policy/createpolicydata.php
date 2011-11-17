<?php
/**
 * Create a policy data row, appending it to the JSON policy data
 *
 * @param string $id The ID of the policy
 * @param string $key The new policy attribute
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
if (!$modx->hasPermission('policy_save')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('policy');

if (!isset($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('policy_err_ns'));

/* get policy */
$policy = $modx->getObject('modAccessPolicy',$scriptProperties['id']);
if ($policy == null) return $modx->error->failure($modx->lexicon('policy_err_nf'));

/* parse data from JSON */
$ar = $policy->get('data');
if (isset($ar[$scriptProperties['key']])) return $modx->error->failure($modx->lexicon('policy_err_ae'));

/* set policy value */
$ar[$scriptProperties['key']] = true;
$policy->set('data',$modx->toJSON($ar));

/* save policy */
if ($policy->save() == false) {
    return $modx->error->failure($modx->lexicon('policy_err_save'));
}

return $modx->error->success();