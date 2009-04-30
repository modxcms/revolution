<?php
/**
 * View a package
 *
 * @param string $id The signature of the package.
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));

$collection= array ();
if (isset($_REQUEST['id']) && $objId= $_REQUEST['id']) {
    if ($package = $modx->getObject('transport.modTransportPackage', $objId)) {
        $oa = $package->toArray();
        $installed = $package->get('installed');

        $oa['installed'] = $installed == null ? $modx->lexicon('no') : $installed;

        $collection[]= $oa;
    }
}
return $modx->error->success('', $collection);