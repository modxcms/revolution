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

/* get rule */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('rule_err_ns'));
$rule = $modx->getObject('modActionDom',$scriptProperties['id']);
if ($rule == null) return $modx->error->failure($modx->lexicon('rule_err_nf'));

$scriptProperties['active'] = !empty($scriptProperties['active']) ? 1 : 0;
$scriptProperties['for_parent'] = !empty($scriptProperties['for_parent']) ? 1 : 0;

/* set fields */
$rule->fromArray($scriptProperties);
$rule->set('action',$scriptProperties['action_id']);

if (isset($scriptProperties['principal'])) {
    /* first remove old access record */
    $access = $modx->getObject('modAccessActionDom',array('target' => $rule->get('id')));
    if ($access) $access->remove();

    /* if changing to a new usergroup, create access record */
    if (!empty($scriptProperties['principal'])) {
        $access = $modx->newObject('modAccessActionDom');
        $access->set('principal',$scriptProperties['principal']);
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
