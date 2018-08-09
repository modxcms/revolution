<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * @package modx
 * @subpackage processors.security.forms.rule
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

$_DATA = $modx->fromJSON($scriptProperties['data']);

/* get rule */
if (empty($_DATA['id'])) return $modx->error->failure($modx->lexicon('rule_err_ns'));
$rule = $modx->getObject('modActionDom',$_DATA['id']);
if ($rule == null) return $modx->error->failure($modx->lexicon('rule_err_nf'));

$_DATA['active'] = !empty($_DATA['active']) ? 1 : 0;
$_DATA['for_parent'] = !empty($_DATA['for_parent']) ? 1 : 0;

/* set fields */
$rule->fromArray($_DATA);
$rule->set('action',$_DATA['action_id']);

if (isset($_DATA['principal'])) {
    /* first remove old access record */
    $access = $modx->getObject('modAccessActionDom',array('target' => $rule->get('id')));
    if ($access) $access->remove();

    /* if changing to a new usergroup, create access record */
    if (!empty($_DATA['principal'])) {
        $access = $modx->newObject('modAccessActionDom');
        $access->set('principal',$_DATA['principal']);
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
