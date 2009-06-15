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

/* validation */
if (empty($_POST['name'])) $modx->error->addField('name',$modx->lexicon('provider_err_ns_name'));
if (empty($_POST['service_url'])) $modx->error->addField('service_url',$modx->lexicon('provider_err_ns_url'));

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* TODO: Check for a valid connection to the provider. */

/* create provider */
$provider = $modx->newObject('transport.modTransportProvider');
$provider->fromArray($_POST);

/* save provider */
if ($provider->save() == false) {
    return $modx->error->failure($modx->lexicon('provider_err_save'));
}

/* log manager action */
$modx->logManagerAction('provider_create','transport.modTransportProvider',$provider->get('id'));

return $modx->error->success('',$provider);