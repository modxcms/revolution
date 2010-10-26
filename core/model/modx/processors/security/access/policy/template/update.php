<?php
/**
 * Updates a policy template
 *
 * @param integer $id The ID of the policy template
 * @param string $name The name of the policy template
 * @param string $description (optional) A short description
 * @param json $data The JSON-encoded policy permissions
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('policy');

/* get policy */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('policy_err_ns'));
$template = $modx->getObject('modAccessPolicyTemplate',$scriptProperties['id']);
if ($template == null) return $modx->error->failure($modx->lexicon('policy_err_nf'));

/* save fields */
$template->fromArray($scriptProperties);


/* now store the permissions into the modAccessPermission table */
/* and cache the data into the policy table */
if (isset($scriptProperties['permissions'])) {
    $permData = array();
    $permissionsArray = $modx->fromJSON($scriptProperties['permissions']);
    
    /* first erase all prior permissions */
    $perms = $template->getMany('Permissions');
    foreach ($perms as $perm) {
        $perm->remove();
    }

    $permissions = array();
    foreach ($permissionsArray as $permArray) {
        $perm = $modx->newObject('modAccessPermission');
        $perm->set('template',$template->get('id'));
        $perm->set('name',$permArray['name']);
        $perm->set('description',$permArray['description']);
        $perm->set('value',true);
        $perm->save();
    }
}

/* save policy */
if ($template->save() == false) {
    return $modx->error->failure($modx->lexicon('policy_template_err_save'));
}

/* log manager action */
$modx->logManagerAction('save_access_policy_template','modAccessPolicyTemplate',$template->get('id'));

return $modx->error->success();