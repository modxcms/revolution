<?php
/**
 * Update a provider from a grid
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('providers')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($_POST['data']);

if (!isset($_DATA['id'])) return $modx->error->failure($modx->lexicon('provider_err_ns'));
$provider = $modx->getObject('transport.modTransportProvider',$_DATA['id']);
if ($provider == null) {
    return $modx->error->failure(sprintf($modx->lexicon('provider_err_nfs'),$_DATA['id']));
}

if (!isset($_DATA['name']) || $_DATA['name'] == '') {
    return $modx->error->failure($modx->lexicon('provider_err_ns_name'));
}
if (!isset($_DATA['service_url']) || $_DATA['service_url'] == '') {
    return $modx->error->failure($modx->lexicon('provider_err_ns_url'));
}

$provider->set('name',$_DATA['name']);
$provider->set('service_url',$_DATA['service_url']);
$provider->set('description',$_DATA['description']);

if ($provider->save() == false) {
    return $modx->error->failure($modx->lexicon('provider_err_save'));
}

/* log manager action */
$modx->logManagerAction('provider_update','transport.modTransportProvider',$provider->get('id'));

return $modx->error->success();