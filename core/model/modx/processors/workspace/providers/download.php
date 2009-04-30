<?php
/**
 * Download packages from a provider
 *
 * @param integer $provider The ID of the provider
 * @param json $packages A JSON array of packages to download
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('providers')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['provider'])) return $modx->error->failure($modx->lexicon('provider_err_ns'));
$provider = $modx->getObject('transport.modTransportProvider',$_POST['provider']);
if ($provider == null) return $modx->error->failure($modx->lexicon('provider_err_nf'));

$_package_cache = $modx->config['core_path'].'packages/';
$pkgs = $modx->fromJSON($_POST['packages']);

$packages = array();
getNodesFormatted($packages,$pkgs);

if (count($packages) == 0) {
    return $modx->error->failure($modx->lexicon('package_download_err_ns'));
}

foreach ($packages as $package) {
    $pkg = $modx->newObject('transport.modTransportPackage');
    $pkg->set('signature',$package['signature']);
    $pkg->set('state',1);
    $pkg->set('workspace',1);

    if (!$pkg->transferPackage($package['location'],$_package_cache)) {
        $modx->log(MODX_LOG_LEVEL_ERROR,'Could not download package at: '.$package['location']);
        continue;
    }
    $pkg->set('created',date('Y-m-d h:i:s'));
    $pkg->set('provider',$provider->get('id'));

    if ($pkg->save() == false) {
        $modx->log(MODX_LOG_LEVEL_ERROR,$modx->lexicon('package_download_err_create',array('signature' => $pkg->get('signature'))));
    }
}

return $modx->error->success();



function getNodesFormatted(&$ar_nodes,$cur_level,$parent = 0) {
    $order = 0;
    foreach ($cur_level as $id => $node) {

        if (isset($node['type']) && $node['type'] == 'version'
         && isset($node['data']) && $node['checked']) {
            $ar_nodes[] = $node['data'];
        }
        if (!empty($node['children'])) {
            getNodesFormatted($ar_nodes,$node['children'],$node['id']);
        }
    }
}