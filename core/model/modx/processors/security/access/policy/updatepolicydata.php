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
 * Update a policy attribute value
 *
 * @param integer $id The ID of the policy
 * @param string $key The attribute key
 * @param boolean $value The value of the attribute
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
if (!$modx->hasPermission('policy_save')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('policy');
if (!isset($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('policy_err_ns'));

/* get policy */
$policy = $modx->getObject('modAccessPolicy',$scriptProperties['id']);
if ($policy == null) return $modx->error->failure($modx->lexicon('policy_err_nf'));

/* parse data from JSON */
$ar = $policy->get('data');

if (!isset($ar[$scriptProperties['key']])) return $modx->error->failure($modx->lexicon('policy_err_nf'));

/* format policy value */
if ($scriptProperties['value'] === 'true') $scriptProperties['value'] = true;
if ($scriptProperties['value'] === 'false') $scriptProperties['value'] = false;

/* set policy value */
$ar[$scriptProperties['key']] = $scriptProperties['value'];

$policy->set('data',$modx->toJSON($ar));

/* save policy */
if ($policy->save() == false) {
    return $modx->error->failure($modx->lexicon('policy_err_save'));
}

return $modx->error->success();
