<?php
/**
 * Gets packages for a provider
 *
 * @param integer $provider The provider ID
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('providers')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';


$provider = $modx->getObject('transport.modTransportProvider',$_REQUEST['provider']);
if ($provider == null) {
    return $modx->error->failure($modx->lexicon('provider_err_nf'));
}
$map = $provider->scanForPackages();

return $modx->error->success('',$map);