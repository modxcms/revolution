<?php
/**
 * Gets an attribute of a package
 *
 * @param string $signature The signature of the package
 * @param string $attr The attribute to select
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['signature'])) return $modx->error->failure($modx->lexicon('package_err_ns'));
$package = $modx->getObject('transport.modTransportPackage',$_REQUEST['signature']);
if ($package == null) return $modx->error->failure($modx->lexicon('package_err_nf'));

$transport = $package->getTransport();
if ($transport) {
    $attr = $transport->getAttribute($_REQUEST['attr']);
} else {
    return $modx->error->failure($modx->lexicon('package_err_nf'));
}

if ($_REQUEST['attr'] == 'setup-options') {
    ob_start();
    $options = $package->toArray();
    $options[XPDO_TRANSPORT_PACKAGE_ACTION] = empty($package->installed)
        ? XPDO_TRANSPORT_ACTION_INSTALL
        : XPDO_TRANSPORT_ACTION_UPGRADE;
    $f = $modx->getOption('core_path').'packages/'.$attr;
    if (file_exists($f) && $attr != '') {
        $attr = include $f;
    } else { $attr = false; }
    ob_end_clean();
}
if ($attr == false) $attr = '';

return $modx->error->success('',array('attr' => $attr));