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
$modx->lexicon->load('workspace');
if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* TODO: remove this line when xPDO::getCount is fixed */
$modx->addPackage('modx.transport',$modx->getOption('core_path').'model/');

$useLimit = !empty($_REQUEST['limit']);
$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
$workspace = !empty($_REQUEST['workspace']) ? $_REQUEST['workspace'] : 1;
$dateFormat = !empty($_REQUEST['dateFormat']) ? $_REQUEST['dateFormat'] : '%b %d, %Y %I:%M %p';

/* get packages */
$c = $modx->newQuery('modTransportPackage');
$c->where(array(
    'workspace' => $workspace,
));
$count = $modx->getCount('modTransportPackage',$c);

$c->sortby('`modTransportPackage`.`disabled`', 'ASC');
$c->sortby('`modTransportPackage`.`signature`', 'ASC');
if ($useLimit) {
    $c->limit($limit,$start);
}
$packages = $modx->getCollection('modTransportPackage',$c);

/* hide prior versions */
$priorVersions = array();
foreach ($packages as $key => $package) {
    $newSig = explode('-',$key);
    $name = $newSig[0];
    $newVers = $newSig[1].'-'.(isset($newSig[2]) ? $newSig[2] : '');
    /* if package already exists, see if later version */
    if (isset($priorVersions[$name])) {
        $oldVers = $priorVersions[$name];
        /* if package is newer and installed, hide old one */
        if (version_compare($oldVers,$newVers,'<') && $package->get('installed')) {
            unset($packages[$name.'-'.$oldVers]);
        }
    }
    $priorVersions[$name] = $newVers;
}

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
    $packageArray['menu'] = array();
    if ($package->get('provider') != 0) {
        $packageArray['menu'][] = array(
            'text' => $modx->lexicon('package_check_for_updates'),
            'handler' => 'this.update',
        );
    }
    $packageArray['menu'][] = array(
        'text' => ($not_installed) ? $modx->lexicon('package_install') : $modx->lexicon('package_reinstall'),
        'handler' => ($not_installed) ? 'this.install' : 'this.install',
    );
    if ($not_installed == false) {
        $packageArray['menu'][] = array(
            'text' => $modx->lexicon('package_uninstall'),
            'handler' => 'this.uninstall',
        );
    }
    $packageArray['menu'][] = '-';
    $packageArray['menu'][] = array(
        'text' => $modx->lexicon('package_remove'),
        'handler' => 'this.remove',
    );


    /* setup readme */
    $transport = $package->getTransport();
    if ($transport) {
        $packageArray['readme'] = $transport->getAttribute('readme');
        $packageArray['readme'] = nl2br($packageArray['readme']);
    }
    $list[] = $packageArray;
}

return $this->outputArray($list,$count);