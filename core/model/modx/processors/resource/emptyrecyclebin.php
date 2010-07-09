<?php
/**
 * Empties the recycle bin.
 *
 * @return boolean
 *
 * @package modx
 * @subpackage processors.resource
 */
if (!$modx->hasPermission('purge_deleted')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource');

/* get resources */
$resources = $modx->getCollection('modResource',array('deleted' => true));
$count = count($resources);

$ids = array();
foreach ($resources as $resource) {
    $ids[] = $resource->get('id');
}

$modx->invokeEvent('OnBeforeEmptyTrash',array(
    'ids' => &$ids,
    'resources' => &$resources,
));

reset($resources);
$ids = array();
foreach ($resources as $resource) {
    if (!$resource->checkPolicy('delete')) continue;

    $resource->groups = $resource->getMany('ResourceGroupResources');
    $resource->tvds = $resource->getMany('TemplateVarResources');

    foreach ($resource->groups as $pair) {
       $pair->remove();
    }

    foreach ($resource->tvds as $tvd) {
        $tvd->remove();
    }

    if ($resource->remove() == false) {
        return $modx->error->failure($modx->lexicon('resource_err_delete'));
    } else {
        $ids[] = $resource->get('id');
    }
}

$modx->invokeEvent('OnEmptyTrash',array(
    'num_deleted' => $count,
    'resources' => &$resources,
    'ids' => &$ids,
));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success();