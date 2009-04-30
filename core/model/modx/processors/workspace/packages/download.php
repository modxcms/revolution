<?php
/**
 * Download a package by passing in its location
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */

if (!isset($_POST['location'])) return $modx->error->failure('No package selected to download.');

$location = $_POST['location'];

/* grab signature from transport zip filename */
$signature = str_replace('.transport.zip','',$location);
$signature = substr($signature,strlen(strrev($signature)) - strpos(strrev($signature),'/'));

$_package_cache = $modx->config['core_path'].'packages/';

/* create transport package object */
$package = $modx->newObject('transport.modTransportPackage');
$package->set('signature',$signature);
$package->set('state',1);
$package->set('workspace',1);
$package->set('created',date('Y-m-d h:i:s'));
$package->set('provider',$_POST['provider']);

/* download package */
if (!$package->transferPackage($location,$_package_cache)) {
    $modx->log(MODX_LOG_LEVEL_ERROR,'Could not download package at: '.$location);
    continue;
}

/* now save */
if ($package->save() == false) {
    $modx->log(MODX_LOG_LEVEL_ERROR,$modx->lexicon('package_download_err_create',array('signature' => $signature)));
}

/* log manager action */
$modx->logManagerAction('package_download','transport.modTransportPackage',$package->get('id'));

return $modx->error->success('',$package);