<?php
/**
 * Gets a provider
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
if (!$modx->hasPermission('providers')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('workspace');

/* get provider */
if (empty($_REQUEST['id']) && empty($_REQUEST['name'])) {
    return $modx->error->failure($modx->lexicon('provider_err_ns'));
}
$c = array();
if (!empty($_REQUEST['id'])) {
    $c['id'] = $_REQUEST['id'];
} else {
    $c['name'] = $_REQUEST['name'];
}
$provider = $modx->getObject('transport.modTransportProvider',$c);
if (!$provider) return $modx->error->failure($modx->lexicon('provider_err_nfs',$c));

/* get provider client */
$loaded = $provider->getClient();
if (!$loaded) return $modx->error->failure($modx->lexicon('provider_err_no_client'));

/* verify provider */
if ($provider->verify()) {
    return $modx->error->success();
} else {
    return $modx->error->failure($modx->lexicon('provider_err_not_verified'));
}