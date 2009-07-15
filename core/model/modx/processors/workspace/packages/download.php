<?php
/**
 * Download a package by passing in its location
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
if (empty($_POST['info'])) return $modx->error->failure($modx->lexicon('package_download_err_ns'));

/* grab location and signature */
$a = explode('::',$_POST['info']);
$location = $a[0];
$signature = $a[1];

$_package_cache = $modx->getOption('core_path').'packages/';

/* create transport package object */
$package = $modx->newObject('transport.modTransportPackage');
$package->set('signature',$signature);
$package->set('state',1);
$package->set('workspace',1);
$package->set('created',date('Y-m-d h:i:s'));
$package->set('provider',$_POST['provider']);

/* download package */
if (!$package->transferPackage($location,$_package_cache)) {
    $msg = $modx->lexicon('package_download_err',array('location' => $location));
    $modx->log(MODX_LOG_LEVEL_ERROR,$msg);
    return $modx->error->failure($msg);
}

/* now save */
if ($package->save() == false) {
    $msg = $modx->lexicon('package_download_err_create',array('signature' => $signature));
    $modx->log(MODX_LOG_LEVEL_ERROR,$msg);
    return $modx->error->failure($msg);
}

return $modx->error->success('',$package);