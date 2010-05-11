<?php
/**
 * Download a package by passing in its location
 *
 * @package modx
 * @subpackage processors.workspace.packages.rest
 */
$modx->lexicon->load('workspace');

if (empty($scriptProperties['info'])) return $modx->error->failure($modx->lexicon('package_download_err_ns'));
if (empty($scriptProperties['provider'])) {
    $c = $modx->newQuery('transport.modTransportProvider');
    $c->where(array('name' => 'modxcms.com'));
    $provider= $modx->getObject('transport.modTransportProvider',$c);
    if (empty($provider)) return $modx->error->failure($modx->lexicon('provider_err_nf'));
    $scriptProperties['provider'] = $provider->get('id');
}


/* grab location and signature */
$a = explode('::',$scriptProperties['info']);
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
$package->set('provider',$scriptProperties['provider']);

/* get provider and metadata */
$provider = $modx->getObject('transport.modTransportProvider',$scriptProperties['provider']);
if (empty($provider)) return $modx->error->failure($modx->lexicon('provider_err_nf'));

/* get provider client */
$loaded = $provider->getClient();
if (!$loaded) return $modx->error->failure($modx->lexicon('provider_err_no_client'));

/* send request to provider and handle response */
$response = $provider->request('package','GET',array(
    'signature' => $signature,
));
if ($response->isError()) {
    return $modx->error->failure($modx->lexicon('provider_err_connect',array('error' => $response->getError())));
}
$metadataXml = $response->toXml();

/* set package metadata */
$metadata = array();
$modx->rest->xml2array($metadataXml,$metadata);
$package->set('metadata',$metadata);
$package->set('package_name',$sig[0]);

/* set package version data */
$sig = explode('-',$signature);
if (is_array($sig)) {
    $package->set('package_name',$sig[0]);
    if (!empty($sig[1])) {
        $v = explode('.',$sig[1]);
        if (isset($v[0])) $package->set('version_major',$v[0]);
        if (isset($v[1])) $package->set('version_minor',$v[1]);
        if (isset($v[2])) $package->set('version_patch',$v[2]);
    }
    if (!empty($sig[2])) {
        $r = preg_split('/([0-9]+)/',$sig[2],-1,PREG_SPLIT_DELIM_CAPTURE);
        if (is_array($r) && !empty($r)) {
            $package->set('release',$r[0]);
            $package->set('release_index',(isset($r[1]) ? $r[1] : '0'));
        } else {
            $package->set('release',$sig[2]);
        }
    }
}

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

$package->getTransport();

return $modx->error->success('',$package);