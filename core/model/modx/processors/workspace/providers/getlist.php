<?php
/**
 * Gets a list of providers
 *
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

$limit = !empty($_REQUEST['limit']);
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('transport.modTransportProvider');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if ($limit) {
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$providers = $modx->getCollection('transport.modTransportProvider',$c);
$count = $modx->getCount('transport.modTransportProvider');

$list = array();
foreach ($providers as $provider) {
    $providerArray = $provider->toArray();
    $providerArray['menu'] = array(
        array(
            'text' => $modx->lexicon('provider_update'),
            'handler' => array( 'xtype' => 'modx-window-provider-update' ),
        ),
        '-',
        array(
            'text' => $modx->lexicon('provider_remove'),
            'handler' => 'this.remove.createDelegate(this,["provider_confirm_remove"])',
        )
    );
    $list[] = $providerArray;
}

return $this->outputArray($list,$count);