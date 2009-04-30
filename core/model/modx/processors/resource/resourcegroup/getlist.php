<?php
/**
 * Grabs a list of resource groups for a resource.
 *
 * @param integer $resource The resource to grab groups for.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.resource.resourcegroup
 */
$modx->lexicon->load('resource');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';

$c = $modx->newQuery('modResourceGroup');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);
$rgs = $modx->getCollection('modResourceGroup',$c);

$count = $modx->getCount('modResourceGroup');

$rs = array();
foreach ($rgs as $rg) {
    $ra = $rg->toArray();

    $rgr = $rg->getOne('modResourceGroupResource',array(
        'document' => $_REQUEST['resource'],
    ));
    $ra['access'] = $rgr != null;

    $rs[] = $ra;
}

return $this->outputArray($rs,$count);