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
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('policy');

/* validate */
if (empty($scriptProperties['name'])) $modx->error->addError('name',$modx->lexicon('policy_err_name_ns'));

/* make sure policy with name does not already exist */
$alreadyExists = $modx->getObject('modAccessPolicy',array(
    'name' => $scriptProperties['name'],
));
if ($alreadyExists) $modx->error->addError('name',$modx->lexicon('policy_err_ae',array('name' => $scriptProperties['name'])));

/* if errors, return */
if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* create new policy object */
$policy = $modx->newObject('modAccessPolicy');
$policy->fromArray($scriptProperties);

/* get policy template and set permissions */
$template = $modx->getObject('modAccessPolicyTemplate',$scriptProperties['template']);
if (!$template) return $modx->error->failure($modx->lexicon('policy_template_err_nf'));

$permissions = $template->getMany('Permissions');
$permList = array();
foreach ($permissions as $permission) {
    $permList[$permission->get('name')] = true;
}
$policy->set('data',$permList);

/* save policy */
if ($policy->save() == false) {
    return $modx->error->failure($modx->lexicon('policy_err_save'));
}

/* log manager action */
$modx->logManagerAction('new_access_policy','modAccessPolicy',$policy->get('id'));

return $modx->error->success();