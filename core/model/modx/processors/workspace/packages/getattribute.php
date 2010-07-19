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

/* get package */
if (empty($scriptProperties['signature'])) return $modx->error->failure($modx->lexicon('package_err_ns'));
$package = $modx->getObject('transport.modTransportPackage',$scriptProperties['signature']);
if ($package == null) return $modx->error->failure();

/* get transport and attribute */
$transport = $package->getTransport();
if ($transport) {
    $attr = $transport->getAttribute($scriptProperties['attr']);
} else {
    return $modx->error->failure($modx->lexicon('package_err_nf'));
}

/* if setup options, include setup file */
if ($scriptProperties['attr'] == 'setup-options') {
    ob_start();
    $options = $package->toArray();
    $options[xPDOTransport::PACKAGE_ACTION] = empty($package->installed)
        ? xPDOTransport::ACTION_INSTALL
        : xPDOTransport::ACTION_UPGRADE;
    $f = $modx->getOption('core_path').'packages/'.$attr;
    if (file_exists($f) && $attr != '') {
        $attr = include $f;
    } else { $attr = false; }
    ob_end_clean();
}
if ($attr == false) $attr = '';

return $modx->error->success('',array('attr' => $attr));