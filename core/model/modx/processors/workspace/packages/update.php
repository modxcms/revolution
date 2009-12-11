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
    $modx->log(modX::LOG_LEVEL_ERROR,$msg);
    return $modx->error->failure($msg);
}
$packageSignature = explode('-',$package->get('signature'));
if ($package->provider != 0) { /* if package has a provider */
    $provider = $package->getOne('Provider');
    if ($provider == null) {
        $msg = $modx->lexicon('provider_err_nf');
        $modx->log(modX::LOG_LEVEL_ERROR,$msg);
        return $modx->error->failure($msg);
    }
} else {
    /* if no provider, output error. you can't update something without a provider! */
    $msg = $modx->lexicon('package_update_err_provider_nf');
    $modx->log(modX::LOG_LEVEL_ERROR,$msg);
    return $modx->error->failure($msg);
}

$modx->log(modX::LOG_LEVEL_INFO,$modx->lexicon('package_update_info_provider_scan',array('provider' => $provider->get('name'))));
$packages = $provider->getUpdatesForPackage($package);

/* if no newer packages were found */
if (count($packages) < 1) {
    $msg = $modx->lexicon('package_err_uptodate',array('signature' => $package->get('signature')));
    $modx->log(modX::LOG_LEVEL_INFO,$msg);
    return $modx->error->failure($msg);
}

$pa = array();
$latest = '';
foreach ($packages as $p) {
    $packageArray = array(
        'id' => (string)$p->id,
        'package' => (string)$p->package,
        'version' => (string)$p->version,
        'release' => (string)$p->release,
        'signature' => (string)$p->signature,
        'location' => (string)$p->location,
        'info' => ((string)$p->location).'::'.((string)$p->signature),
    );
    $pa[] = $packageArray;
}

return $modx->error->success('',$pa);