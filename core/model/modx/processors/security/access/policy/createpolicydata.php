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
$modx->lexicon->load('policy');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['id'])) return $modx->error->failure('Policy id not specified!');

/* get policy */
$policy = $modx->getObject('modAccessPolicy',$_REQUEST['id']);
if ($policy == null) return $modx->error->failure('Policy not found!');

/* parse data from JSON */
$ar = $policy->get('data');
if (isset($ar[$_REQUEST['key']])) return $modx->error->failure('Policy property already exists!');

/* set policy value */
$ar[$_REQUEST['key']] = true;
$policy->set('data',$modx->toJSON($ar));

/* save policy */
if ($policy->save() == false) {
    return $modx->error->failure('An error occurred while saving the policy data.');
}

return $modx->error->success();