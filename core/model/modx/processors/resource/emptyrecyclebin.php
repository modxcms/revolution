<?php
/**
 * Empties the recycle bin.
 *
 * @return boolean
 *
 * @package modx
 * @subpackage processors.resource
 */
$modx->lexicon->load('resource');

if (!$modx->hasPermission('purge_deleted')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get resources */
$resources = $modx->getCollection('modResource',array('deleted' => true));

foreach ($resources as $resource) {
	$resource->groups = $resource->getMany('modResourceGroupResource');
	$resource->tvds = $resource->getMany('modTemplateVarResource');

	foreach ($resource->groups as $pair) {
	   $pair->remove();
    }

	foreach ($resource->tvds as $tvd) {
		$tvd->remove();
    }

	if ($resource->remove() == false) {
		return $modx->error->failure($modx->lexicon('resource_err_delete'));
    }

	/* see if resource's parent has any children left */
	$parent = $modx->getObject('modResource',$resource->get('parent'));
	if ($parent != null && $parent->get('id') != null) {
		$num_children = $modx->getCount('modResource',array('parent' => $parent->get('id')));
		if ($num_children <= 0) {
			$parent->set('isfolder',false);
			$parent->save();
		}
	}
}

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success();