<?php
/**
 * Removes multiple policy templates
 *
 * @param integer $templates A comma-separated list of policy templates
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('policy');

if (empty($scriptProperties['templates'])) {
    return $modx->error->failure($modx->lexicon('policy_template_err_ns'));
}

$templateIds = explode(',',$scriptProperties['templates']);
$core = array('Resource','Object','Administrator','Element');

foreach ($templateIds as $templateId) {
    /* get policy */
    $template = $modx->getObject('modAccessPolicyTemplate',$templateId);
    if ($template == null) return $modx->error->failure($modx->lexicon('policy_err_nf'));

    /* remove policy */
    if ($template->remove() == false) {
        return $modx->error->failure($modx->lexicon('policy_template_err_remove'));
    }

    /* log manager action */
    $modx->logManagerAction('remove_access_policy_template','modAccessPolicyTemplate',$template->get('id'));
}

return $modx->error->success();
