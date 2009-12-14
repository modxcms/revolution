<?php
/**
 * Download a package by passing in its location
 *
 * @package modx
 * @subpackage processors.workspace.packages.rest
 */
if (empty($_POST['info'])) return $modx->error->failure($modx->lexicon('package_download_err_ns'));

if (empty($_POST['provider'])) $_POST['provider'] = 2;


/* grab location and signature */
$a = explode('::',$_POST['info']);
$location = $a[0];
$signature = $a[1];
$sig = explode('-',$signature);

$_package_cache = $modx->getOption('core_path').'packages/';

/* create transport package object */
$package = $modx->newObject('transport.modTransportPackage');
$package->set('signature',$signature);
$package->set('state',1);
$package->set('workspace',1);
$package->set('created',date('Y-m-d h:i:s'));
$package->set('provider',$_POST['provider']);

/* get provider and metadata */
$provider = $modx->getObject('transport.modTransportProvider',$_POST['provider']);
if (empty($provider)) return $modx->error->failure($modx->lexicon('provider_err_nfs',$c));

/* get provider client */
$loaded = $provider->getClient();
if (!$loaded) return $modx->error->failure($modx->lexicon('provider_err_no_client'));

/* send request to provider and handle response */
$response = $provider->request('package','GET',array(
    'signature' => $signature,
));
$metadataXml = $provider->handleResponse($response);
if (!$metadataXml) return $modx->error->failure($modx->lexicon('provider_err_connect'));


/* set package metadata */
$metadata = array();
$modx->rest->xml2array($metadataXml,$metadata);
$package->set('metadata',$metadata);
$package->set('package_name',$sig[0]);

/* download package */
if (!$package->transferPackage($location,$_package_cache)) {
    $msg = $modx->lexicon('package_download_err',array('location' => $location));
    $modx->log(modX::LOG_LEVEL_ERROR,$msg);
    return $modx->error->failure($msg);
}

/* now save */
if ($package->save() == false) {
    $msg = $modx->lexicon('package_download_err_create',array('signature' => $signature));
    $modx->log(modX::LOG_LEVEL_ERROR,$msg);
    return $modx->error->failure($msg);
}

return $modx->error->success('',$package);