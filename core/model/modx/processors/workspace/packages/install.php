<?php
/**
 * Install a package
 *
 * @param string $signature The signature of the package.
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('workspace');

$modx->log(modX::LOG_LEVEL_INFO,$modx->lexicon('package_install_info_start',array('signature' => $scriptProperties['signature'] )));

/* find package */
if (empty($scriptProperties['signature'])) return $modx->error->failure($modx->lexicon('package_err_ns'));
$package= $modx->getObject('transport.modTransportPackage',$scriptProperties['signature']);
if ($package == null) {
    $modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');
    return $modx->error->failure(sprintf($modx->lexicon('package_err_nfs'),$scriptProperties['signature']));
}

$modx->log(xPDO::LOG_LEVEL_INFO,$modx->lexicon('package_install_info_found'));

/* install package */
$installed = $package->install($scriptProperties);

/* empty cache */
$modx->cacheManager->refresh(array($modx->getOption('cache_packages_key', null, 'packages') => array()));
$modx->cacheManager->refresh();

if (!$installed) {
    $msg = $modx->lexicon('package_err_install',array('signature' => $package->get('signature')));
    $modx->log(modX::LOG_LEVEL_ERROR,$msg);
    $modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');
    return $modx->error->failure($msg);
} else {
    $msg = $modx->lexicon('package_install_info_success',array('signature' => $package->get('signature')));
    $modx->log(modX::LOG_LEVEL_WARN,$msg);
    $modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');
    return $modx->error->success($msg);
}
$modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');
return $modx->error->failure($modx->lexicon('package_err_install_gen'));