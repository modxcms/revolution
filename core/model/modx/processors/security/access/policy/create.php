<?php
/**
 * Create an access policy.
 *
 * @param string $name The name of the policy.
 * @param string $description (optional) A short description
 * @param integer $parent (optional) A parent policy
 * @param string $class
 * @param json $data The JSON-encoded policy data
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('policy');

/* validate */
if (empty($scriptProperties['name'])) $modx->error->addError('name',$modx->lexicon('policy_err_name_ns'));

/* make sure policy with name does not already exist */
$ae = $modx->getObject('modAccessPolicy',array(
    'name' => $scriptProperties['name'],
));
if ($ae != null) $modx->error->addError('name',$modx->lexicon('policy_err_ae'));

/* if errors, return */
if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* create new policy object */
$policy = $modx->newObject('modAccessPolicy');
$policy->fromArray($scriptProperties);

/* save policy */
if ($policy->save() == false) {
    return $modx->error->failure('Error saving policy!');
}

/* log manager action */
$modx->logManagerAction('new_access_policy','modAccessPolicy',$policy->get('id'));

return $modx->error->success();