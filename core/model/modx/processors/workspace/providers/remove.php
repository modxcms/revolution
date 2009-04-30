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

if (!isset($_POST['id'])) return $modx->error->failure($modx->lexicon('provider_err_ns'));
$provider = $modx->getObject('transport.modTransportProvider',$_POST['id']);
if ($provider == null) {
    return $modx->error->failure(sprintf($modx->lexicon('provider_err_nfs'),$_POST['id']));
}

if ($provider->remove() == false) {
    return $modx->error->failure($modx->lexicon('provider_err_remove'));
}

/* log manager action */
$modx->logManagerAction('provider_remove','transport.modTransportProvider',$provider->get('id'));

return $modx->error->success();