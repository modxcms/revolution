<?php
/**
 * Update a provider from a grid
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('providers')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($scriptProperties['data']);

/* get provider */
if (empty($_DATA['id'])) return $modx->error->failure($modx->lexicon('provider_err_ns'));
$provider = $modx->getObject('transport.modTransportProvider',$_DATA['id']);
if ($provider == null) {
    return $modx->error->failure($modx->lexicon('provider_err_nfs',array('id' => $_DATA['id'])));
}

/* validation */
if (empty($_DATA['name'])) $modx->error->addField('name',$modx->lexicon('provider_err_ns_name'));
if (empty($_DATA['service_url'])) $modx->error->addField('service_url',$modx->lexicon('provider_err_ns_url'));

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* set fields and save */
$provider->fromArray($_DATA);
if ($provider->save() == false) {
    return $modx->error->failure($modx->lexicon('provider_err_save'));
}

/* log manager action */
$modx->logManagerAction('provider_update','transport.modTransportProvider',$provider->get('id'));

return $modx->error->success();