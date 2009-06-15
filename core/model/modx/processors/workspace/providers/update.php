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

/* validation */
if (empty($_POST['name'])) $modx->error->addField('name',$modx->lexicon('provider_err_ns_name'));
if (empty($_POST['service_url'])) $modx->error->addField('service_url',$modx->lexicon('provider_err_ns_url'));

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* get provider */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('provider_err_ns'));
$provider = $modx->getObject('transport.modTransportProvider',$_POST['id']);
if ($provider == null) return $modx->error->failure($modx->lexicon('provider_err_nfs',array('id' => $_POST['id'])));

/* TODO: Check for a valid connection to the provisioner. */

/* set and save provider */
$provider->fromArray($_POST);
if ($provider->save() == false) {
    return $modx->error->failure($modx->lexicon('provider_err_save'));
}

/* log manager action */
$modx->logManagerAction('provider_update','transport.modTransportProvider',$provider->get('id'));

return $modx->error->success('',$provider);