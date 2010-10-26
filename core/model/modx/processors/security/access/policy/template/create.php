<?php
/**
 * Create an access policy template
 *
 * @param string $name The name of the policy.
 * @param string $description (optional) A short description
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('policy');

/* validate */
if (empty($scriptProperties['name'])) $modx->error->addError('name',$modx->lexicon('policy_template_err_name_ns'));

/* make sure policy with name does not already exist */
$alreadyExists = $modx->getObject('modAccessPolicyTemplate',array(
    'name' => $scriptProperties['name'],
));
if ($alreadyExists) $modx->error->addError('name',$modx->lexicon('policy_template_err_ae',array('name' => $scriptProperties['name'])));

/* if errors, return */
if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* create new policy object */
$template = $modx->newObject('modAccessPolicyTemplate');
$template->fromArray($scriptProperties);

/* save policy */
if ($template->save() == false) {
    return $modx->error->failure($modx->lexicon('policy_template_err_save'));
}

/* log manager action */
$modx->logManagerAction('new_access_policy_template','modAccessPolicyTemplate',$template->get('id'));

return $modx->error->success();