<?php
/**
 * Removes a policy template
 *
 * @param integer $id The ID of the policy template
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('policy');

/* get policy */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('policy_template_err_ns'));
$template = $modx->getObject('modAccessPolicyTemplate', $scriptProperties['id']);
if ($template == null) return $modx->error->failure($modx->lexicon('policy_template_err_nf'));

/* remove policy template */
if ($template->remove() == false) {
    return $modx->error->failure($modx->lexicon('policy_err_remove'));
}

/* log manager action */
$modx->logManagerAction('remove_access_policy_template','modAccessPolicyTemplate',$template->get('id'));

return $modx->error->success();