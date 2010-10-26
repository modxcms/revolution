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
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('policy_err_ns'));
$oldPolicy = $modx->getObject('modAccessPolicy',$scriptProperties['id']);
if ($oldPolicy == null) return $modx->error->failure($modx->lexicon('policy_err_nf'));

/* duplicate policy */
$newPolicy = $modx->newObject('modAccessPolicy');
$newPolicy->fromArray($oldPolicy->toArray('',true), '', false, true);
$newPolicy->set('name',$modx->lexicon('duplicate_of',array(
    'name' => $newPolicy->get('name'),
)));

/* save new policy */
if ($newPolicy->save() === false) {
    return $modx->error->failure($modx->lexicon('policy_err_duplicate'));
}

/* log manager action */
$modx->logManagerAction('policy_duplicate','modAccessPolicy',$newPolicy->get('id'));

return $modx->error->success('',$newPolicy);