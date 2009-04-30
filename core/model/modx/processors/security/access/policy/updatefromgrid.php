<?php
/**
 * Update a policy from a grid
 *
 * @param integer $id The ID of the policy
 * @param string $name The name of the policy.
 * @param string $description (optional) A short description
 * @param integer $parent (optional) A parent policy
 * @param string $class
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
$modx->lexicon->load('policy');
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($_POST['data']);

if (!isset($_DATA['id'])) {
    return $modx->error->failure('Policy id not specified!');
}
$id = $_DATA['id'];

if ($policy = $modx->getObject('modAccessPolicy', $id)) {
    $policy->fromArray($_DATA);
    if ($policy->save() === false) {
        return $modx->error->failure('Error saving policy!');
    }
} else {
    return $modx->error->failure('Could not locate policy for update!');
}


/* log manager action */
$modx->logManagerAction('save_access_policy','modAccessPolicy',$policy->get('id'));

return $modx->error->success();