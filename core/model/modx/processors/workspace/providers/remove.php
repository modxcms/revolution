<?php
/**
 * Remove a provider
 *
 * @param integer $id The provider ID
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('providers')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get provider */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('provider_err_ns'));
$provider = $modx->getObject('transport.modTransportProvider',$scriptProperties['id']);
if ($provider == null) {
    return $modx->error->failure($modx->lexicon('provider_err_nfs',array('id' => $scriptProperties['id'])));
}

/* remove provider */
if ($provider->remove() == false) {
    return $modx->error->failure($modx->lexicon('provider_err_remove'));
}

/* log manager action */
$modx->logManagerAction('provider_remove','transport.modTransportProvider',$provider->get('id'));

return $modx->error->success();