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

if (!isset($_REQUEST['workspace'])) $_REQUEST['workspace'] = 1;


$c = $modx->newQuery('transport.modTransportPackage', array ('workspace' => $_REQUEST['workspace']));
$c->sortby('`modTransportPackage`.`disabled`', 'ASC');
$c->sortby('`modTransportPackage`.`signature`', 'ASC');
$packages = $modx->getCollection('transport.modTransportPackage',$c);


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
$ps = array();
foreach ($packages as $package) {

    if ($package->get('installed') == '0000-00-00 00:00:00') $package->set('installed',null);
    $pa = $package->toArray();

    /* format timestamps */
    if ($package->get('updated') != '0000-00-00 00:00:00' && $package->get('updated') != null) {
        $pa['updated'] = strftime('%b %d, %Y %I:%M %p',strtotime($package->get('updated')));
    } else {
        $pa['updated'] = '';
    }
    $pa['created']= strftime('%b %d, %Y %I:%M %p',strtotime($package->get('created')));
    if ($package->get('installed') == null || $package->get('installed') == '0000-00-00 00:00:00') {
        $not_installed = true;
        $pa['installed'] = null;
    } else {
        $not_installed = false;
        $pa['installed'] = strftime('%b %d, %Y %I:%M %p',strtotime($package->get('installed')));
    }

    /* setup menu */
    $not_installed = $package->get('installed') == null || $package->get('installed') == '0000-00-00 00:00:00';
    $pa['menu'] = array();
    if ($package->get('provider') != 0) {
        $pa['menu'][] = array(
            'text' => $modx->lexicon('package_check_for_updates'),
            'handler' => 'this.update',
        );
    }
    $pa['menu'][] = array(
        'text' => ($not_installed) ? $modx->lexicon('package_install') : $modx->lexicon('package_reinstall'),
        'handler' => ($not_installed) ? 'this.install' : 'this.install',
    );
    if ($not_installed == false) {
        $pa['menu'][] = array(
            'text' => $modx->lexicon('package_uninstall'),
            'handler' => 'this.uninstall',
        );
    }
    $pa['menu'][] = '-';
    $pa['menu'][] = array(
        'text' => $modx->lexicon('package_remove'),
        'handler' => 'this.remove',
    );


    /* setup readme */
    $transport = $package->getTransport();
    if ($transport) {
        $pa['readme'] = $transport->getAttribute('readme');
        $pa['readme'] = nl2br($pa['readme']);
    }
    $ps[] = $pa;
}

return $this->outputArray($ps);