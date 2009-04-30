<?php
/**
 * Create a provider
 *
 * @param string $name The name of the provider
 * @param string $description A short description
 * @param string $service_url The URL the provider is hosted under
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('providers')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['name']) || $_POST['name'] == '') {
    return $modx->error->failure($modx->lexicon('provider_err_ns_name'));
}
if (!isset($_POST['service_url']) || $_POST['service_url'] == '') {
    return $modx->error->failure($modx->lexicon('provider_err_ns_url'));
}

/* TODO: Check for a valid connection to the provider. */

$provider = $modx->newObject('transport.modTransportProvider');
$provider->set('name',$_POST['name']);
$provider->set('description',$_POST['description']);
$provider->set('service_url',$_POST['service_url']);

if ($provider->save() == false) {
    return $modx->error->failure($modx->lexicon('provider_err_save'));
}

/* log manager action */
$modx->logManagerAction('provider_create','transport.modTransportProvider',$provider->get('id'));

return $modx->error->success('',$provider);