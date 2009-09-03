<?php
/**
 * @package modx
 * @subpackage processors.security.forms.rule
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

$rule = $modx->newObject('modActionDom');
$rule->fromArray($_POST);
$rule->set('action',$_POST['action_id']);

if ($rule->save() == false) {
    return $modx->error->failure($modx->lexicon('rule_err_save'));
}

if (!empty($_POST['principal'])) {
    $access = $modx->newObject('modAccessAction');
    $access->set('principal',$_POST['principal']);
    $access->set('principal_class','modUserGroup');
    $access->set('target',$rule->get('id'));
    $access->set('authority',9999);
    $access->set('policy',0);
    $access->save();
}

return $modx->error->success('',$rule);