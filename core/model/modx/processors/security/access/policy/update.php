<?php
/**
 * Updates a policy
 *
 * @param integer $id The ID of the policy
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

/* get policy */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('policy_err_ns'));
$policy = $modx->getObject('modAccessPolicy',$scriptProperties['id']);
if ($policy == null) return $modx->error->failure($modx->lexicon('policy_err_nf'));

/* save fields */
$policy->fromArray($scriptProperties);

/* now store the permissions into the modAccessPermission table */
/* and cache the data into the policy table */
if (isset($scriptProperties['permissions'])) {
    $permData = array();
    $permissionsArray = $modx->fromJSON($scriptProperties['permissions']);

    $permissions = array();
    foreach ($permissionsArray as $permissionArray) {
        $permData[$permissionArray['name']] = $permissionArray['enabled'] ? true : false;
    }
    $policy->set('data',$permData);
}

/* save policy */
if ($policy->save() == false) {
    return $modx->error->failure($modx->lexicon('policy_err_save'));
}

/* log manager action */
$modx->logManagerAction('save_access_policy','modAccessPolicy',$policy->get('id'));

return $modx->error->success();