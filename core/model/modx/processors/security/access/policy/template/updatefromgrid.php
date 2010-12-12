<?php
/**
 * Update a policy template from a grid
 *
 * @param integer $id The ID of the policy
 * @param string $name The name of the policy.
 * @param string $description (optional) A short description
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('policy');

/* parse JSON data */
$_DATA = $modx->fromJSON($scriptProperties['data']);

/* get policy */
if (empty($_DATA['id'])) return $modx->error->failure($modx->lexicon('policy_template_err_ns'));
$template = $modx->getObject('modAccessPolicyTemplate', $_DATA['id']);
if (!$template) return $modx->error->failure($modx->lexicon('policy_template_err_nf'));

/* set and save data */
$template->fromArray($_DATA);

if ($template->save() === false) {
    return $modx->error->failure($modx->lexicon('policy_template_err_save'));
}

/* log manager action */
$modx->logManagerAction('save_access_policy_template','modAccessPolicyTemplate',$template->get('id'));

return $modx->error->success();