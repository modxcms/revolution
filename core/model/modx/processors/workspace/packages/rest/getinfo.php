<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages.rest
 */
if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('workspace');

$provider = $modx->getOption('provider',$_REQUEST,false);
if (empty($provider)) return $modx->error->failure($modx->lexicon('provider_err_ns'));
$provider = $modx->getObject('transport.modTransportProvider',$provider);
if (empty($provider)) return $modx->error->failure($modx->lexicon('provider_err_nf'));

/* get version */
$modx->getVersionData();
$productVersion = $modx->version['code_name'].'-'.$modx->version['full_version'];

/* get provider client */
$loaded = $provider->getClient();
if (!$loaded) return $modx->error->failure($modx->lexicon('provider_err_no_client'));

/* send request to provider and handle response */
$response = $provider->request('home','GET',array(
    'supports' => $productVersion,
));
$info = $provider->handleResponse($response);
if (!$info) return $modx->error->failure($modx->lexicon('provider_err_connect'));

/* setup output properties */
$properties = array(
    'packages' => number_format((string)$info->packages),
    'downloads' => number_format((string)$info->downloads),
    'topdownloaded' => array(),
    'newest' => array(),
);

foreach ($info->topdownloaded as $package) {
    $properties['topdownloaded'][] = array(
        'name' => (string)$package->name,
        'downloads' => number_format((string)$package->downloads,0),
    );
}

foreach ($info->newest as $package) {
    $properties['newest'][] = array(
        'name' => (string)$package->name,
        'releasedon' => strftime('%b %d, %Y',strtotime((string)$package->releasedon)),
    );
}

return $modx->error->success('',$properties);