<?php
/**
 * Duplicates a policy template
 *
 * @param integer $id The ID of the policy template
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('policy');

/* Get old policy */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('policy_template_err_ns'));
$oldPolicyTemplate = $modx->getObject('modAccessPolicyTemplate',$scriptProperties['id']);
if ($oldPolicyTemplate == null) return $modx->error->failure($modx->lexicon('policy_template_err_nf'));

/* duplicate policy */
$newPolicyTemplate = $modx->newObject('modAccessPolicyTemplate');
$newPolicyTemplate->fromArray($oldPolicyTemplate->toArray('',true), '', false, true);
$newPolicyTemplate->set('name',$modx->lexicon('duplicate_of',array(
    'name' => $newPolicyTemplate->get('name'),
)));
$permissions = $oldPolicyTemplate->getMany('Permissions');

/* save new policy */
if ($newPolicyTemplate->save() === false) {
    return $modx->error->failure($modx->lexicon('policy_template_err_duplicate'));
}

/* save new permissions for template */
if (!empty($permissions)) {
    foreach ($permissions as $permission) {
        $newPerm = $modx->newObject('modAccessPermission');
        $newPerm->set('name',$permission->get('name'));
        $newPerm->set('description',$permission->get('description'));
        $newPerm->set('value',$permission->get('value'));
        $newPerm->set('template',$newPolicyTemplate->get('id'));
        $newPerm->save();
    }
}

/* log manager action */
$modx->logManagerAction('policy_template_duplicate','modAccessPolicyTemplate',$newPolicyTemplate->get('id'));

return $modx->error->success('',$newPolicyTemplate);