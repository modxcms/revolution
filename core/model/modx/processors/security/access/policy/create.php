<?php
/**
 * Create an access policy.
 *
 * @param string $name The name of the policy.
 * @param string $description (optional) A short description
 * @param integer $parent (optional) A parent policy
 * @param string $class
 * @param json $data The JSON-encoded policy data
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
$modx->lexicon->load('policy');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

$name = isset($_POST['name']) ? $_POST['name'] : '';
if (!$name) {
    return $modx->error->failure('No name specified for policy!');
}

$c = array('name' => $name);
$policy = $modx->getObject('modAccessPolicy', $c);
if ($policy === null) {
    $policy = $modx->newObject('modAccessPolicy');
    $policy->fromArray($_POST);
    if ($policy->save() == false) {
        return $modx->error->failure('Error saving policy!');
    }
} else {
    return $modx->error->failure('Policy already exists!');
}

/* log manager action */
$modx->logManagerAction('new_access_policy','modAccessPolicy',$policy->get('id'));

return $modx->error->success();