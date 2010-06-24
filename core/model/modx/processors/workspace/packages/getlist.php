<?php
/**
 * Gets a list of packages
 *
 * @param integer $workspace (optional) The workspace to filter by. Defaults to
 * 1.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('workspace');
$modx->addPackage('modx.transport',$modx->getOption('core_path').'model/');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$workspace = $modx->getOption('workspace',$scriptProperties,1);
$dateFormat = $modx->getOption('dateFormat',$scriptProperties,'%b %d, %Y %I:%M %p');

/* get packages */
$c = $modx->newQuery('transport.modTransportPackage');
$c->leftJoin('transport.modTransportProvider','Provider');
$c->where(array(
    'workspace' => $workspace,
));
$c->where(array(
    '(SELECT
        `signature`
      FROM '.$modx->getTableName('modTransportPackage').' AS `latestPackage`
      WHERE `latestPackage`.`package_name` = `modTransportPackage`.`package_name`
      ORDER BY
         `latestPackage`.`version_major` DESC,
         `latestPackage`.`version_minor` DESC,
         `latestPackage`.`version_patch` DESC,
         IF(`release` = "" OR `release` = "ga" OR `release` = "pl","z",`release`) DESC,
         `latestPackage`.`release_index` DESC
      LIMIT 1) = `modTransportPackage`.`signature`',
));
$count = $modx->getCount('modTransportPackage',$c);
$c->select(array(
    'modTransportPackage.*',
));
$c->select('`Provider`.`name` AS `provider_name`');
$c->sortby('modTransportPackage.signature', 'ASC');
if ($isLimit) $c->limit($limit,$start);
$packages = $modx->getCollection('transport.modTransportPackage',$c);

$updatesCacheExpire = $modx->getOption('auto_check_pkg_updates_cache_expire',$scriptProperties,5) * 60;

$modx->getVersionData();
$productVersion = $modx->version['code_name'].'-'.$modx->version['full_version'];
$providerCache = array();

/* now create output array */
$list = array();
foreach ($packages as $key => $package) {
    if ($package->get('installed') == '0000-00-00 00:00:00') $package->set('installed',null);

    $packageArray = $package->toArray();

    $signatureArray = explode('-',$key);
    $packageArray['name'] = $signatureArray[0];
    $packageArray['version'] = $signatureArray[1];
    if (isset($signatureArray[2])) {
        $packageArray['release'] = $signatureArray[2];
    }

    /* format timestamps */
    if ($package->get('updated') != '0000-00-00 00:00:00' && $package->get('updated') != null) {
        $packageArray['updated'] = strftime($dateFormat,strtotime($package->get('updated')));
    } else {
        $packageArray['updated'] = '';
    }
    $packageArray['created']= strftime($dateFormat,strtotime($package->get('created')));
    if ($package->get('installed') == null || $package->get('installed') == '0000-00-00 00:00:00') {
        $not_installed = true;
        $packageArray['installed'] = null;
    } else {
        $not_installed = false;
        $packageArray['installed'] = strftime($dateFormat,strtotime($package->get('installed')));
    }

    /* setup menu */
    $not_installed = $package->get('installed') == null || $package->get('installed') == '0000-00-00 00:00:00';
    $packageArray['iconaction'] = $not_installed ? 'icon-install' : 'icon-uninstall';
    $packageArray['textaction'] = $not_installed ? $modx->lexicon('install') : $modx->lexicon('uninstall');

    /* setup description, using either metadata or readme */
    $metadata = $package->get('metadata');
    if (!empty($metadata)) {
        foreach ($metadata as $row) {
            if (!empty($row['name']) && $row['name'] == 'description') {
                $packageArray['readme'] = nl2br($row['text']);
                break;
            }
        }
    } else {
        $transport = $package->getTransport();
        if ($transport) {
            $packageArray['readme'] = $transport->getAttribute('readme');
            $packageArray['readme'] = nl2br($packageArray['readme']);
        }
    }
    unset($packageArray['attributes']);
    unset($packageArray['metadata']);


    /* check for updates */
    $updates = array('count' => 0);
    if ($package->get('provider') > 0 && $modx->getOption('auto_check_pkg_updates',null,false)) {
        $updateCacheKey = 'mgr/providers/updates/'.$package->get('provider').'/'.$package->get('signature');

        $updates = $modx->cacheManager->get($updateCacheKey);
        if (empty($updates)) {
            /* cache providers to speed up load time */
            if (!empty($providerCache[$package->get('provider')])) {
                $provider =& $providerCache[$package->get('provider')];
            } else {
                $provider = $package->getOne('Provider');
                if ($provider) {
                    $providerCache[$provider->get('id')] = $provider;
                }
            }
            if ($provider) {
                $loaded = $provider->getClient();
                if ($loaded) {
                    $response = $provider->request('package/update','GET',array(
                        'signature' => $package->get('signature'),
                        'supports' => $productVersion,
                    ));
                    if ($response && !$response->isError()) {
                        $updates = $response->toXml();
                    }
                }
                $updates = array('count' => count($updates));
                $modx->cacheManager->set($updateCacheKey,$updates,$updatesCacheExpire);
            }
        }
    }
    $packageArray['updateable'] = $updates['count'] >= 1 ? true : false;

    $list[] = $packageArray;
}

return $this->outputArray($list,$count);