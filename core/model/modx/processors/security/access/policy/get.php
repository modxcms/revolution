<?php
/**
 * Gets a policy
 *
 * @param integer $id The ID of the policy
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
$modx->lexicon->load('policy');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('policy_err_ns'));
$policy = $modx->getObject('modAccessPolicy', $scriptProperties['id']);
if ($policy == null) return $modx->error->failure($modx->lexicon('policy_err_nf'));


/* get permissions for policy */
$c = $modx->newQuery('modAccessPermission');
$c->where(array(
    'policy' => $policy->get('id'),
));
$c->sortby('name','ASC');
$permissions = $policy->getMany('Permissions',$c);

$policyArray = $policy->get(array(
    'id',
    'name',
    'description',
    'class',
    'parent',
));

$list = array();
foreach ($permissions as $permission) {
    $list[] = array(
        $permission->get('name'),
        $permission->get('description'),
        $permission->get('value'),
    );
}
$policyArray['permissions'] = $list;

return $modx->error->success('', $policyArray);