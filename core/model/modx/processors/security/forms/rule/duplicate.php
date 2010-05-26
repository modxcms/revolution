<?php
/**
 * @package modx
 * @subpackage processors.security.forms.rule
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

/* get rule */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('rule_err_ns'));
$rule = $modx->getObject('modActionDom',$scriptProperties['id']);
if ($rule == null) return $modx->error->failure($modx->lexicon('rule_err_nf'));

$newRule = $modx->newObject('modActionDom');
$newRule->fromArray($rule->toArray());

/* save new rule */
if ($newRule->save() == false) {
    return $modx->error->failure($modx->lexicon('rule_err_duplicate'));
}

return $modx->error->success('',$newRule);