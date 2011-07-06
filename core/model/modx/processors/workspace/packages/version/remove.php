<?php
/**
 * Remove a package
 *
 * @param string $signature The signature of the package.
 * @param boolean $force (optional) If true, will remove the package even if
 * uninstall fails. Defaults to false.
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('workspace');

/* get package */
$modx->log(xPDO::LOG_LEVEL_INFO,$modx->lexicon('package_remove_info_gpack'));
$package = $modx->getObject('transport.modTransportPackage', $scriptProperties['signature']);
if ($package == null) return $modx->error->failure($modx->lexicon('package_err_nfs',array('signature' => $scriptProperties['signature'])));

$modx->log(xPDO::LOG_LEVEL_INFO,$modx->lexicon('package_remove_info_tzip_start'));

/* remove transport package */
if ($package->remove() == false) {
    $modx->log(xPDO::LOG_LEVEL_ERROR,$modx->lexicon('package_err_remove'));
    return $modx->error->failure($modx->lexicon('package_err_remove',array('signature' => $package->getPrimaryKey())));
}

/* empty cache */
$modx->cacheManager->refresh(array($modx->getOption('cache_packages_key', null, 'packages') => array()));
$modx->cacheManager->refresh();

/* remove transport zip */
$f = $modx->getOption('core_path').'packages/'.$package->signature.'.transport.zip';
if (!file_exists($f)) {
    $modx->log(xPDO::LOG_LEVEL_ERROR,$modx->lexicon('package_remove_err_tzip_nf'));
} else if (!@unlink($f)) {
    $modx->log(xPDO::LOG_LEVEL_ERROR,$modx->lexicon('package_remove_err_tzip'));
} else {
    $modx->log(xPDO::LOG_LEVEL_INFO,$modx->lexicon('package_remove_info_tzip'));
}
$modx->log(xPDO::LOG_LEVEL_INFO,$modx->lexicon('package_remove_info_tdir_start'));

/* remove transport dir */
$f = $modx->getOption('core_path').'packages/'.$package->signature.'/';
if (!file_exists($f)) {
    $modx->log(xPDO::LOG_LEVEL_ERROR,$modx->lexicon('package_remove_err_tdir_nf'));
} else if (!$modx->cacheManager->deleteTree($f,true,false,array())) {
    $modx->log(xPDO::LOG_LEVEL_ERROR,$modx->lexicon('package_remove_err_tdir'));
} else {
    $modx->log(xPDO::LOG_LEVEL_INFO,$modx->lexicon('package_remove_info_tdir'));
}

$modx->log(modX::LOG_LEVEL_WARN,$modx->lexicon('package_remove_info_success'));
sleep(2);
$modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');
return $modx->error->success();