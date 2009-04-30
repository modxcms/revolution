<?php
/**
 * Update a package from its provider.
 *
 * @param string $signature The signature of the package.
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));

$package = $modx->getObject('transport.modTransportPackage',$_REQUEST['signature']);
if ($package == null) {
    $msg = $modx->lexicon('package_err_nf');
    $modx->log(XPDO_LOG_LEVEL_ERROR,$msg);
    return $modx->error->failure($msg);
}
$packageSignature = explode('-',$package->get('signature'));
if ($package->provider != 0) { /* if package has a provider */
    $provider = $package->getOne('Provider');
    if ($provider == null) {
        $msg = $modx->lexicon('provider_err_nf');
        $modx->log(MODX_LOG_LEVEL_ERROR,$msg);
        return $modx->error->failure($msg);
    }
} else {
    /* if no provider, output error. you can't update something without a provider! */
    $msg = $modx->lexicon('package_update_err_provider_nf');
    $modx->log(XPDO_LOG_LEVEL_ERROR,$msg);
    return $modx->error->failure($msg);
}

$modx->log(MODX_LOG_LEVEL_INFO,$modx->lexicon('package_update_info_provider_scan',array('provider' => $provider->get('name'))));
$packages = $provider->getUpdatesForPackage($package);

/* an error occurred */
if (!is_array($packages)) {
    return $modx->error->failure($packages);
}

/* if no newer packages were found */
if (count($packages) < 1) {
    $msg = $modx->lexicon('package_err_uptodate',array('signature' => $package->get('signature')));
    $modx->log(MODX_LOG_LEVEL_INFO,$msg);
    return $modx->error->failure($msg);
}

$pa = array();
$latest = '';
foreach ($packages as $p) {
    /* get rid of manifest to cut down on data outputs */
    unset($p['manifest']);
    $pa[] = $p;
}

/* log manager action */
$modx->logManagerAction('package_update','transport.modTransportPackage',$package->get('id'));

return $modx->error->success('',$pa);