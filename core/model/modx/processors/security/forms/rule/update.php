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

/* set fields */
$rule->fromArray($_POST);
$rule->set('action',$_POST['action_id']);

if (isset($_POST['principal'])) {
    /* first remove old access record */
    $access = $modx->getObject('modAccessActionDom',array('target' => $rule->get('id')));
    if ($access) $access->remove();

    /* if changing to a new usergroup, create access record */
    if (!empty($_POST['principal'])) {
        $access = $modx->newObject('modAccessActionDom');
        $access->set('principal',$_POST['principal']);
        $access->set('principal_class','modUserGroup');
        $access->set('target',$rule->get('id'));
        $access->set('authority',9999);
        $access->set('policy',0);
        $access->save();
    }
}

/* save rule */
if ($rule->save() == false) {
    return $modx->error->failure($modx->lexicon('rule_err_save'));
}

return $modx->error->success('',$rule);