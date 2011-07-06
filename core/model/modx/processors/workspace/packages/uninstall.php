<?php
/**
 * Uninstall a package
 *
 * @param string $signature The signature of the package.
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (empty($scriptProperties['signature'])) return $modx->error->failure($modx->lexicon('package_err_ns'));
$modx->log(modX::LOG_LEVEL_INFO,$modx->lexicon('package_uninstall_info_find',array('signature' => $scriptProperties['signature'])));
$package = $modx->getObject('transport.modTransportPackage', $scriptProperties['signature']);
if (empty($package)) {
    return $modx->error->failure($modx->lexicon('package_err_nfs',array(
        'signature' =>  $scriptProperties['signature'],
    )));
}
$transport = $package->getTransport();

$modx->log(modX::LOG_LEVEL_INFO,$modx->lexicon('package_uninstall_info_prep'));

/* uninstall package */
$options = array(
    xPDOTransport::PREEXISTING_MODE => $scriptProperties['preexisting_mode'],
);

if ($package->uninstall($options) == false) {
    return $modx->error->failure(sprintf($modx->lexicon('package_err_uninstall'),$package->getPrimaryKey()));
}

$modx->log(modX::LOG_LEVEL_WARN,$modx->lexicon('package_uninstall_info_success',array('signature' => $package->get('signature'))));
sleep(2);
$modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');

/* empty cache */
$modx->cacheManager->refresh(array($modx->getOption('cache_packages_key', null, 'packages') => array()));
$modx->cacheManager->refresh();

/* log manager action */
$modx->logManagerAction('package_uninstall','transport.modTransportPackage',$package->get('id'));

return $modx->error->success();