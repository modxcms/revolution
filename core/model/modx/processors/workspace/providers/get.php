<?php
/**
 * Gets a provider
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('providers')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get provider */
if (empty($scriptProperties['id']) && empty($scriptProperties['name'])) {
    return $modx->error->failure($modx->lexicon('provider_err_ns'));
}
$c = array();
if (!empty($scriptProperties['id'])) {
    $c['id'] = $scriptProperties['id'];
} else {
    $c['name'] = $scriptProperties['name'];
}
$provider = $modx->getObject('transport.modTransportProvider',$c);
if ($provider == null) return $modx->error->failure($modx->lexicon('provider_err_nfs',$c));

return $modx->error->success('',$provider);