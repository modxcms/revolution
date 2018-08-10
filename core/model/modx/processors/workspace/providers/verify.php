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
 * Gets a provider
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
if (!$modx->hasPermission('providers')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('workspace');

/* get provider */
if (empty($scriptProperties['id']) && empty($scriptProperties['name'])) {
    return $modx->error->failure($modx->lexicon('provider_err_ns'));
}
$c = array();
if (!empty($scriptProperties['id'])) {
    $c['id'] = $scriptProperties['id'];
} else {
    $c['name'] = $scriptProperties['name'];
}
$provider = $modx->getObject('transport.modTransportProvider',$c);
if (!$provider) return $modx->error->failure($modx->lexicon('provider_err_nfs',$c));

/* get provider client */
$loaded = $provider->getClient();
if (!$loaded) return $modx->error->failure($modx->lexicon('provider_err_no_client'));

/* verify provider */
$verified = $provider->verify();
if ($verified === true) {
    return $modx->error->success();
} else {
    return $modx->error->failure($verified);
}
