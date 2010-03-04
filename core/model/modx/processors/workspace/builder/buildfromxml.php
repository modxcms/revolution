<?php
/**
 * @package modx
 * @subpackage processors.workspace.builder
 */
$modx->lexicon->load('workspace','package_builder');

if (!$modx->hasPermission('package_builder')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* load the modXMLPackageBuilder class and get an instance */
$modx->log(MODX_LOG_LEVEL_INFO,'Loading package builder.');
$modx->loadClass('transport.modXMLPackageBuilder','',false, true);
$builder = new modXMLPackageBuilder($modx);

if (!isset($scriptProperties['file'])) {
    $modx->log(MODX_LOG_LEVEL_ERROR,$modx->lexicon('xml_file_err_upload'));
	return $modx->error->failure($modx->lexicon('xml_file_err_upload'));
}
$_FILE = $scriptProperties['file'];
if (!isset($scriptProperties['error']) || $scriptProperties['error'] != '0') {
    $modx->log(MODX_LOG_LEVEL_ERROR,$modx->lexicon('xml_file_err_upload'));
	return $modx->error->failure($modx->lexicon('xml_file_err_upload'));
}

/* build the package */
$modx->log(MODX_LOG_LEVEL_INFO,'Attempting to build the package.');
if ($builder->build($_FILE['tmp_name']) === false) {
    $modx->log(MODX_LOG_LEVEL_ERROR,$modx->lexicon('package_build_err'));
	return $modx->error->failure($modx->lexicon('package_build_err'));
}

$filename = $modx->getOption('core_path').'packages/'.$builder->getSignature().'.transport.zip';

$modx->log(MODX_LOG_LEVEL_WARN,$modx->lexicon('package_built').' - '.$filename);
return $modx->error->success($modx->lexicon('package_built').' - '.$filename);