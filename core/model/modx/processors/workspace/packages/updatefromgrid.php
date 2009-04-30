<?php
/**
 * Update a package information from the grid
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($_POST['data']);

$package = $modx->getObject('transport.modTransportPackage',array(
    'signature' => $_DATA['signature'],
));

$package->fromArray($_DATA);
if ($package->save() === false) {
    return $modx->error->failure($modx->lexicon('package_err_save'));
}

/* log manager action */
$modx->logManagerAction('package_update','transport.modTransportPackage',$package->get('id'));

return $modx->error->success();