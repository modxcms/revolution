<?php
/**
 * Activate multiple FC rules
 *
 * @package modx
 * @subpackage processors.security.forms.rule
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

if (empty($scriptProperties['rules'])) {
    return $modx->error->failure($modx->lexicon('rule_err_ns'));
}

$ruleIds = explode(',',$scriptProperties['rules']);

foreach ($ruleIds as $ruleId) {
    $rule = $modx->getObject('modActionDom',$ruleId);
    if ($rule == null) continue;

    $rule->set('active',true);

    if ($rule->save() === false) {
        return $modx->error->failure($modx->lexicon('rule_err_save'));
    }
}

return $modx->error->success();
