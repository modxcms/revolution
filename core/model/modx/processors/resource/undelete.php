<?php
/**
 * Undeletes a resource.
 *
 * @param integer $id The ID of the resource
 * @return array An array with the ID of the undeleted resource
 *
 * @package modx
 * @subpackage processors.resource
 */
if (!$modx->hasPermission('undelete_document')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource');

if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('resource_err_ns'));
$resource = $modx->getObject('modResource',$scriptProperties['id']);
if (empty($resource)) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $scriptProperties['id'])));

/* check permissions on the resource */
if (!$resource->checkPolicy(array('save'=>1, 'undelete'=>1))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

$locked = $resource->addLock();
if ($locked !== true) {
    $user = $modx->getObject('modUser', $locked);
    if ($user) {
        return $modx->error->failure($modx->lexicon('resource_locked_by', array('id' => $resource->get('id'), 'user' => $user->get('username'))));
    }
}

function getChildren(&$modx,$parent,$deltime = 0) {
    if (empty($deltime)) $deltime = time();
    $success = false;

    $kids = $modx->getCollection('modResource',array(
        'parent' => $parent,
        'deleted' => true,
    ));

    if(count($kids) > 0) {
        /* the resource has children resources, we'll need to undelete those too */
        foreach ($kids as $kid) {
            $locked = $kid->addLock();
            if ($locked !== true) {
                $user = $modx->getObject('modUser', $locked);
                if ($user) $modx->error->failure($modx->lexicon('resource_locked_by', array('id' => $kid->get('id'), 'user' => $user->get('username'))));
            }
            $kid->set('deleted',0);
            $kid->set('deletedby',0);
            $kid->set('deletedon',0);
            $success = $kid->save();
            if ($success) {
                $success = getChildren($modx,$kid->get('id'),$deltime);
            }
        }
    }
    return $success;
}

if (!getChildren($modx,$resource->get('id'),$resource->get('deletedon'))) {
    $resource->removeLock();
    $modx->error->failure($modx->lexicon('resource_err_undelete_children'));
}
/* 'undelete' the resource. */

$resource->set('deleted',0);
$resource->set('deletedby',0);
$resource->set('deletedon',0);

if ($resource->save() == false) {
    $resource->removeLock();
    return $modx->error->failure($modx->lexicon('resource_err_undelete'));
}

$modx->invokeEvent('OnResourceUndelete',array(
    'id' => $resource->get('id'),
    'resource' => &$resource,
));

/* log manager action */
$modx->logManagerAction('undelete_resource','modResource',$resource->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

$resource->removeLock();

return $modx->error->success('',$resource->get(array('id')));