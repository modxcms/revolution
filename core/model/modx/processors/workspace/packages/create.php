<?php
/**
 * Creates a transport package
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));

$package = $modx->newObject('transport.modTransportPackage');
$package->fromArray($scriptProperties, '', true, false);
$package->set('state', 1);
if ($package->save() == false) {
    return $modx->error->failure($modx->lexicon('package_err_create'));
}

/* log manager action */
$modx->logManagerAction('package_create','transport.modTransportPackage',$package->get('id'));


return $modx->error->success('', $package->get(array ('signature')));