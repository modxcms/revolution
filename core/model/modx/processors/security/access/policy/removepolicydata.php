<?php
/**
 * Removes a policy attribute
 *
 * @param $id The ID of the policy
 * @param $key The key of the attribute data row
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
if (!isset($ar[$scriptProperties['key']])) return $modx->error->failure($modx->lexicon('policy_err_nf'));

/* set policy value */
unset($ar[$scriptProperties['key']]);
$policy->set('data',$modx->toJSON($ar));

/* save policy */
if ($policy->save() == false) {
    return $modx->error->failure($modx->lexicon('policy_err_save'));
}
return $modx->error->success();