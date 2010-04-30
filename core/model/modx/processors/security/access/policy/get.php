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
    'lexicon',
    'class',
    'parent',
));
if (!empty($policyArray['lexicon'])) {
    $modx->lexicon->load($policyArray['lexicon']);
}

$list = array();
foreach ($permissions as $permission) {
    $desc = $permission->get('description');
    if (!empty($policyArray['lexicon'])) {
        $desc = $modx->lexicon($desc);
    }
    $list[] = array(
        $permission->get('name'),
        $permission->get('description'),
        $desc,
        $permission->get('value'),
    );
}
$policyArray['permissions'] = $list;

return $modx->error->success('', $policyArray);