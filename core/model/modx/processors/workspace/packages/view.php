<?php
/**
 * View a package
 *
 * @param string $id The signature of the package.
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('workspace');

$collection= array ();
if (isset($scriptProperties['id']) && $objId= $scriptProperties['id']) {
    if ($package = $modx->getObject('transport.modTransportPackage', $objId)) {
        $oa = $package->toArray();
        $installed = $package->get('installed');

        $oa['installed'] = $installed == null ? $modx->lexicon('no') : $installed;

        $collection[]= $oa;
    }
}
return $modx->error->success('', $collection);