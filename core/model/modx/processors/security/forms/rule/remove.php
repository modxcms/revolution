<?php
/**
 * @package modx
 * @subpackage processors.security.forms.rule
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

/* get rule */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('rule_err_ns'));
$rule = $modx->getObject('modActionDom',$_POST['id']);
if ($rule == null) return $modx->error->failure($modx->lexicon('rule_err_nf'));

/* remove rule */
if ($rule->remove() == false) {
    return $modx->error->failure($modx->lexicon('rule_err_remove'));
}

return $modx->error->success('',$rule);