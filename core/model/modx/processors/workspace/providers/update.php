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
if (!$modx->hasPermission('providers')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('workspace');

/* validation */
if (empty($scriptProperties['name'])) $modx->error->addField('name',$modx->lexicon('provider_err_ns_name'));
if (empty($scriptProperties['service_url'])) $modx->error->addField('service_url',$modx->lexicon('provider_err_ns_url'));

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* get provider */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('provider_err_ns'));
$provider = $modx->getObject('transport.modTransportProvider',$scriptProperties['id']);
if ($provider == null) return $modx->error->failure($modx->lexicon('provider_err_nfs',array('id' => $scriptProperties['id'])));

/* set and save provider */
$provider->fromArray($scriptProperties);

/* verify provider */
$verified = $provider->verify();
if ($verified !== true) {
    return $modx->error->failure($verified);
}

if ($provider->save() == false) {
    return $modx->error->failure($modx->lexicon('provider_err_save'));
}

/* log manager action */
$modx->logManagerAction('provider_update','transport.modTransportProvider',$provider->get('id'));

return $modx->error->success('',$provider);