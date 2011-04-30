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
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$resourceId = $modx->getOption('resource',$scriptProperties,0);

/* get resource */
if (empty($resourceId)) {
    $resource = $modx->newObject('modResource');
    $resource->set('id',0);
} else {
    $resource = $modx->getObject('modResource', $resourceId);
    if (empty($resource)) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $resourceId)));

    /* check access */
    if (!$resource->checkPolicy('view')) {
        return $modx->error->failure($modx->lexicon('permission_denied'));
    }
}

$resourceGroupList = $resource->getGroupsList(array($sort => $dir), $isLimit ? $limit : 0, $start);
$resourceGroups = $resourceGroupList['collection'];

$list = array();
foreach ($resourceGroups as $resourceGroup) {
    $resourceGroupArray = $resourceGroup->toArray();
    $resourceGroupArray['access'] = (boolean) $resourceGroupArray['access'];
    $list[] = $resourceGroupArray;
}

return $this->outputArray($list, $resourceGroupList['total']);