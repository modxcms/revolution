<?php
/**
 * @package modx
 */
if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('workspace');

$provider = $modx->getOption('provider',$scriptProperties,false);
if (empty($provider)) return $modx->error->failure($modx->lexicon('provider_err_ns'));

$provider = $modx->getObject('transport.modTransportProvider',$provider);
if (empty($provider)) return $modx->error->failure($modx->lexicon('provider_err_nf'));

if (empty($scriptProperties['query']) && empty($scriptProperties['tag'])) return $this->outputArray(array());

/* get default properties */
$tag = $modx->getOption('tag',$scriptProperties,false);
$query = $modx->getOption('query',$scriptProperties,false);
$sorter = $modx->getOption('sorter',$scriptProperties,false);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$page = !empty($start) ? round($start / $limit) : 0;

/* get version */
$modx->getVersionData();
$productVersion = $modx->version['code_name'].'-'.$modx->version['full_version'];

/* get provider client */
$loaded = $provider->getClient();
if (!$loaded) return $modx->error->failure($modx->lexicon('provider_err_no_client'));

/* send request and process response */
$where = array(
    'page' => $page,
    'supports' => $productVersion,
    'sorter' => $sorter,
);
if (!empty($tag)) $where['tag'] = $tag;
if (!empty($query)) $where['query'] = $query;

$response = $provider->request('package','GET',$where);
if ($response->isError()) {
    return $modx->error->failure($modx->lexicon('provider_err_connect',array('error' => $response->getError())));
}
$tag = $response->toXml();

/* iterate through packages */
$total = (int)$tag['total'];
$list = array();
foreach ($tag->package as $package) {
    if ((string)$package->name == '') continue;

    $installed = $modx->getObject('transport.modTransportPackage',(string)$package->signature);

    $versionCompiled = rtrim((string)$package->version.'-'.(string)$package->release,'-');
    $releasedon = strftime('%b %d, %Y',strtotime((string)$package->releasedon));

    $supports = '';
    foreach ($package->supports as $support) {
        $supports .= (string)$support.', ';
    }


    $list[] = array(
        'id' => (string)$package->id,
        'version' => (string)$package->version,
        'release' => (string)$package->release,
        'signature' => (string)$package->signature,
        'author' => (string)$package->author,
        'description' => (string)$package->description,
        'instructions' => (string)$package->instructions,
        'createdon' => (string)$package->createdon,
        'editedon' => (string)$package->editedon,
        'name' => (string)$package->name,
        'downloads' => (string)$package->downloads,
        'releasedon' => $releasedon,
        'screenshot' => (string)$package->screenshot,
        'thumbnail' => !empty($package->thumbnail) ? (string)$package->thumbnail : (string)$package->screenshot,
        'license' => (string)$package->license,
        'supports' => rtrim($supports,', '),
        'location' => (string)$package->location,
        'version-compiled' => $versionCompiled,
        'downloaded' => !empty($installed) ? true : false,
        'dlaction-icon' => $installed ? 'package-installed' : 'package-download',
        'dlaction-text' => $installed ? $modx->lexicon('downloaded') : $modx->lexicon('download'),
    );
}

return $this->outputArray($list,$total);