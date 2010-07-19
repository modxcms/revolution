<?php
/**
 * Gets a list of packages
 *
 * @package modx
 * @subpackage processors.workspace.package
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
$signature = $modx->getOption('signature',$scriptProperties,false);

if (empty($signature)) return $this->outputArray(array());
$signatureArray = explode('-',$signature);

/* get packages */
$c = $modx->newQuery('transport.modTransportPackage');
$c->select('
    `modTransportPackage`.*,
    `Provider`.`name` AS `provider_name`
');
$c->leftJoin('transport.modTransportProvider','Provider');
$c->where(array(
    'workspace' => $workspace,
    'package_name' => $signatureArray[0],
));
$count = $modx->getCount('modTransportPackage',$c);
$c->sortby('`modTransportPackage`.`version_major`', 'DESC');
$c->sortby('`modTransportPackage`.`version_minor`', 'DESC');
$c->sortby('`modTransportPackage`.`version_patch`', 'DESC');
$c->sortby('IF(`modTransportPackage`.`release` = "" OR `modTransportPackage`.`release` = "ga" OR `modTransportPackage`.`release` = "pl","z",`release`) DESC','');
$c->sortby('`modTransportPackage`.`release_index`', 'DESC');
if ($isLimit) $c->limit($limit,$start);
$packages = $modx->getCollection('transport.modTransportPackage',$c);

/* now create output array */
$list = array();
$i = 0;
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

    if ($i > 0 || !$package->get('installed')) {
        $packageArray['menu'] = array();
        $packageArray['menu'][] = array(
            'text' => $modx->lexicon('package_version_remove'),
            'handler' => 'this.removePriorVersion',
        );
    }

    /* setup description, using either metadata or readme */
    $metadata = $package->get('metadata');
    if (!empty($metadata)) {
        foreach ($metadata as $row) {
            if (!empty($row['name']) && $row['name'] == 'description') {
                $packageArray['readme'] = str_replace('<br /><br />','<br />',str_replace("\n",'',nl2br($row['text'])));
                break;
            }
        }
    } else {
        $transport = $package->getTransport();
        if ($transport) {
            $packageArray['readme'] = $transport->getAttribute('readme');
            $packageArray['readme'] = str_replace('<br /><br />','<br />',str_replace("\n",'',nl2br($packageArray['readme'])));
        }
    }
    unset($packageArray['attributes']);
    unset($packageArray['metadata']);
    unset($packageArray['manifest']);
    $list[] = $packageArray;
    if ($package->get('installed') != null) $i++;
}

return $this->outputArray($list,$count);

return array();