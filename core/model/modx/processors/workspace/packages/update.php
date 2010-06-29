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

/* get package */
if (empty($scriptProperties['signature'])) return $modx->error->failure($modx->lexicon('package_err_ns'));
$package = $modx->getObject('transport.modTransportPackage',$scriptProperties['signature']);
if (!$package) return $modx->error->failure($modx->lexicon('package_err_nf'));

$package->fromArray($scriptProperties);

if (!$package->save()) {
    return $modx->error->failure($modx->lexicon('package_err_save'));
}

return $modx->error->success('',$package);