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
if (!$modx->hasPermission('list')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource');

/* setup default properties */
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,10);
$mode = $modx->getOption('mode',$_REQUEST,'name');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$resource = $modx->getOption('resource',$_REQUEST,false);

if (empty($resource)) return $this->error->failure($modx->lexicon('resource_err_ns'));

/* build query */
$c = $modx->newQuery('modResourceGroup');
$c->leftJoin('modResourceGroupResource','rgr','
    rgr.document_group = modResourceGroup.id
AND rgr.document = '.$_REQUEST['resource']);
$c->select('
    modResourceGroup.*,
    IF(ISNULL(rgr.document),0,1) AS access
');
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$resourceGroups = $modx->getCollection('modResourceGroup',$c);
$count = $modx->getCount('modResourceGroup');


/* iterate */
$list = array();
foreach ($resourceGroups as $resourceGroup) {
    $resourceGroupArray = $resourceGroup->toArray();
    $resourceGroupArray['access'] = (boolean)$resourceGroupArray['access'];
    $list[] = $resourceGroupArray;
}

return $this->outputArray($list,$count);