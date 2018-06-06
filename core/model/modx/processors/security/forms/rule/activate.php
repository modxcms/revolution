<?php
/**
 * Activate a FC rule
 *
 * @package modx
 * @subpackage processors.security.forms.rule
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('rule_err_ns'));
$rule = $modx->getObject('modActionDom',$scriptProperties['id']);
if ($rule == null) return $modx->error->failure($modx->lexicon('rule_err_nf'));

$rule->set('active',true);

if ($rule->save() === false) {
    return $modx->error->failure($modx->lexicon('rule_err_save'));
}

return $modx->error->success();
