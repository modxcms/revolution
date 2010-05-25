<?php
/**
 * Gets a chunk.
 *
 * @param integer $id The ID of the chunk.
 *
 * @package modx
 * @subpackage processors.element.chunk
 */
if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('workspace');
$modx->addPackage('modx.transport',$modx->getOption('core_path').'model/');

$dateFormat = $modx->getOption('dateFormat',$scriptProperties,'%b %d, %Y %I:%M %p');

/* get package */
if (empty($scriptProperties['signature'])) return $modx->error->failure($modx->lexicon('package_err_ns'));
$c = $modx->newQuery('transport.modTransportPackage');
$c->select('
    `modTransportPackage`.*,
    `Provider`.`name` AS `provider_name`
');
$c->leftJoin('transport.modTransportProvider','Provider');
$c->where(array(
    'signature' => $scriptProperties['signature'],
));
$package = $modx->getObject('transport.modTransportPackage',$c);
if (!$package) return $modx->error->failure($modx->lexicon('package_err_nf'));

$packageArray = $package->toArray();

/* get attributes */
$transport = $package->getTransport();
if ($transport) {
    $packageArray['readme'] = $transport->getAttribute('readme');
    $packageArray['license'] = $transport->getAttribute('license');
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

unset($packageArray['attributes']);
unset($packageArray['metadata']);
unset($packageArray['manifest']);
return $modx->error->success('',$packageArray);