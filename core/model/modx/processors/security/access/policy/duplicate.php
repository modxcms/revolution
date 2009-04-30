<?php
/**
 * Duplicates a policy
 *
 * @param integer $id The ID of the policy
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
$modx->lexicon->load('policy');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* Get old policy */
$old_policy = $modx->getObject('modAccessPolicy',$_REQUEST['id']);
if ($old_policy == null) return $modx->error->failure($modx->lexicon('policy_err_nf'));

/* duplicate policy */
$policy = $modx->newObject('modAccessPolicy');
$policy->fromArray($old_policy->toArray('',true), '', false, true);
$policy->set('name',$modx->lexicon('duplicate_of').$policy->get('name'));

if ($policy->save() === false) {
    return $modx->error->failure($modx->lexicon('policy_err_duplicate'));
}

/* log manager action */
$modx->logManagerAction('policy_duplicate','modAccessPolicy',$policy->get('id'));

return $modx->error->success();