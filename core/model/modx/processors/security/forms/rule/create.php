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

$scriptProperties['active'] = !empty($scriptProperties['active']) ? 1 : 0;
$scriptProperties['for_parent'] = !empty($scriptProperties['for_parent']) ? 1 : 0;

$rule = $modx->newObject('modActionDom');
$rule->fromArray($scriptProperties);
$rule->set('action',$scriptProperties['action_id']);

if ($rule->save() == false) {
    return $modx->error->failure($modx->lexicon('rule_err_save'));
}

if (!empty($scriptProperties['principal'])) {
    $access = $modx->newObject('modAccessActionDom');
    $access->set('principal',$scriptProperties['principal']);
    $access->set('principal_class','modUserGroup');
    $access->set('target',$rule->get('id'));
    $access->set('authority',9999);
    $access->set('policy',0);
    $access->save();
}

return $modx->error->success('',$rule);
