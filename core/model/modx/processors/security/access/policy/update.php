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

/* wipe all the current permissions for that policy */
$permissions = $modx->removeCollection('modAccessPermission',array(
    'policy' => $policy->get('id'),
));

/* now store the permissions into the modAccessPermission table */
/* and cache the data into the policy table */
if (isset($scriptProperties['permissions'])) {
    $permData = array();
    $permissionsArray = $modx->fromJSON($scriptProperties['permissions']);

    $permissions = array();
    foreach ($permissionsArray as $permissionArray) {
        $permission = $modx->newObject('modAccessPermission');
        $permission->set('name',$permissionArray['name']);
        $permission->set('description',$permissionArray['description']);
        $permission->set('value',1);

        $permissions[] = $permission;
        /* feed into cache array for policy table */
        $permData[$permissionArray['name']] = true;
    }

    $policy->addMany($permissions);
    $policy->set('data',$permData);
}

/* save policy */
if ($policy->save() == false) {
    return $modx->error->failure($modx->lexicon('policy_err_save'));
}

/* log manager action */
$modx->logManagerAction('save_access_policy','modAccessPolicy',$policy->get('id'));

return $modx->error->success();