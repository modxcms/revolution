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
if (!$modx->hasPermission('providers')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('workspace');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$isCombo = !empty($scriptProperties['combo']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

/* build query */
$c = $modx->newQuery('transport.modTransportProvider');
$count = $modx->getCount('transport.modTransportProvider',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$providers = $modx->getCollection('transport.modTransportProvider',$c);

/* iterate */
$list = array();
if ($isCombo) {
    $list[] = array('id' => 0,'name' => $modx->lexicon('none'));
}
foreach ($providers as $provider) {
    $providerArray = $provider->toArray();
    if (!$isCombo) {
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
    }
    $list[] = $providerArray;
}

return $this->outputArray($list,$count);