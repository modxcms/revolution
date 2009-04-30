<?php
/**
 * Update a provider
 *
 * @param integer $id The ID of the provider
 * @param string $name The new name for the provider
 * @param string $description A short description
 * @param string $service_url The URL which the provider is hosted under
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

if (!isset($_POST['id'])) return $modx->error->failure($modx->lexicon('provider_err_ns'));
$provider = $modx->getObject('transport.modTransportProvider',$_POST['id']);
if ($provider == null) return $modx->error->failure(sprintf($modx->lexicon('provider_err_nfs'),$_POST['id']));

/* TODO: Check for a valid connection to the provisioner. */

$provider->set('name',$_POST['name']);
$provider->set('description',$_POST['description']);
$provider->set('service_url',$_POST['service_url']);

if ($provider->save() == false) {
    return $modx->error->failure($modx->lexicon('provider_err_save'));
}

/* log manager action */
$modx->logManagerAction('provider_update','transport.modTransportProvider',$provider->get('id'));

return $modx->error->success('',$provider);