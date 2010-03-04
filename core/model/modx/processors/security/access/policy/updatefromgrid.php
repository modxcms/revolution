<?php
/**
 * Update a policy from a grid
 *
 * @param integer $id The ID of the policy
 * @param string $name The name of the policy.
 * @param string $description (optional) A short description
 * @param integer $parent (optional) A parent policy
 * @param string $class
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('policy');

/* parse JSON data */
$_DATA = $modx->fromJSON($scriptProperties['data']);

/* get policy */
if (empty($_DATA['id'])) return $modx->error->failure($modx->lexicon('policy_err_ns'));
$policy = $modx->getObject('modAccessPolicy', $_DATA['id']);
if ($policy == null) return $modx->error->failure($modx->lexicon('policy_err_nf'));

/* set and save data */
$policy->fromArray($_DATA);

if ($policy->save() === false) {
    return $modx->error->failure($modx->lexicon('policy_err_save'));
}

/* log manager action */
$modx->logManagerAction('save_access_policy','modAccessPolicy',$policy->get('id'));

return $modx->error->success();