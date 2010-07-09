<?php
/**
 * Deletes a resource.
 *
 * @param integer $id The ID of the resource
 * @return array
 *
 * @package modx
 * @subpackage processors.resource
 */
if (!$modx->hasPermission('delete_document')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource');

$deltime = time();

/* get resource */
$resource = $modx->getObject('modResource', $scriptProperties['id']);
if (empty($resource)) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $scriptProperties['id'])));

/* validate resource can be deleted */
if (!$resource->checkPolicy(array('save' => true, 'delete' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
if ($modx->getOption('site_start') == $resource->get('id')) {
    return $modx->error->failure($modx->lexicon('resource_err_delete_sitestart'));
}
if ($modx->getOption('site_unavailable_page') == $resource->get('id')) {
    return $modx->error->failure($modx->lexicon('resource_err_delete_siteunavailable'));
}

/* check for locks on resource */
$locked = $resource->addLock();
if ($locked !== true) {
    $user = $modx->getObject('modUser', $locked);
    if ($user) {
        return $modx->error->failure($modx->lexicon('resource_locked_by', array('id' => $resource->get('id'), 'user' => $user->get('username'))));
    }
}

$children = array();
getChildren($resource, $modx, $children);
function getChildren($parent, &$modx, &$children) {
    if (!is_array($children)) {
        $children= array();
    }
    $parent->children = $parent->getMany('Children');
    if (count($parent->children) > 0) {
        foreach ($parent->children as $child) {
            if ($child->get('id') == $modx->getOption('site_start')) {
                return $modx->error->failure($modx->lexicon('resource_err_delete_container_sitestart',array('id' => $child->get('id'))));
            }
            if ($child->get('id') == $modx->getOption('site_unavailable_page')) {
                return $modx->error->failure($modx->lexicon('document_err_delete_container_siteunavailable',array('id' => $child->get('id'))));
            }

            $children[] = $child;

            /* recursively loop through tree */
            getChildren($child, $modx, $children);
        }
    }
}

/* prepare children ids for invokeEvents */
$childrenIds = array ();
foreach ($children as $child) {
    $childrenIds[] = $child->get('id');
}

/* invoke OnBeforeDocFormDelete event */
$modx->invokeEvent('OnBeforeDocFormDelete', array (
    'id' => $resource->get('id'),
    'resource' => &$resource,
    'children' => $childrenIds,
));

/* delete children */
if (count($children) > 0) {
    foreach ($children as $child) {
        $locked = $child->addLock();
        if ($locked !== true) {
            $user = $modx->getObject('modUser', $locked);
            if ($user) return $modx->error->failure($modx->lexicon('resource_locked_by', array('id' => $child->get('id'), 'user' => $user->get('username'))));
        }
        $child->set('deleted', 1);
        $child->set('deletedby', $modx->user->get('id'));
        $child->set('deletedon', $deltime);
        if ($child->save() == false) {
            $child->removeLock();
            $resource->removeLock();
            return $modx->error->failure($modx->lexicon('resource_err_delete_children'));
        }
    }
}

/* delete the document. */
$resource->set('deleted', 1);
$resource->set('deletedby', $modx->user->get('id'));
$resource->set('deletedon', $deltime);
if ($resource->save() == false) {
    $resource->removeLock();
    return $modx->error->failure($modx->lexicon('resource_err_delete'));
}

/* invoke OnDocFormDelete event */
$modx->invokeEvent('OnDocFormDelete', array (
    'id' => $resource->get('id'),
    'children' => $childrenIds,
    'resource' => &$resource,
));


$modx->invokeEvent('OnResourceDelete',array(
    'id' => $resource->get('id'),
    'children' => &$childrenIds,
    'resource' => &$resource,
));

/* log manager action */
$modx->logManagerAction('delete_resource','modDocument',$resource->get('id'));

/* empty cache */
$cacheManager = $modx->getCacheManager();
$cacheManager->clearCache();

$resource->removeLock();

return $modx->error->success('', $resource->get(array (
    'id',
    'deleted',
    'deletedby',
    'deletedon'
)));