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
