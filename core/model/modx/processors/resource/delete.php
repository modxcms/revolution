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
$modx->lexicon->load('resource');

$deltime = time();

if (!$modx->hasPermission('delete_document')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get resource */
$resource = $modx->getObject('modResource', $_REQUEST['id']);
if ($resource == null) {
    return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $_REQUEST['id'])));
}

if (!$resource->checkPolicy(array('save'=>1, 'delete'=>1)))
    return $modx->error->failure($modx->lexicon('permission_denied'));

if ($modx->config['site_start'] == $resource->get('id')) {
    return $modx->error->failure($modx->lexicon('resource_err_delete_sitestart'));
}
if ($modx->config['site_unavailable_page'] == $resource->get('id')) {
    return $modx->error->failure($modx->lexicon('resource_err_delete_siteunavailable'));
}

$ar_children = array ();
getChildren($resource, $modx, $ar_children);
function getChildren($parent, & $modx, & $ar_children) {
    if (!is_array($ar_children)) {
        $ar_children= array();
    }
    $parent->children = $parent->getMany('Children');
    if (count($parent->children) > 0) {
        foreach ($parent->children as $child) {
            if ($child->get('id') == $modx->config['site_start']) {
                return $modx->error->failure($modx->lexicon('resource_err_delete_container_sitestart',array('id' => $child->get('id'))));
            }
            if ($child->get('id') == $modx->config['site_unavailable_page']) {
                return $modx->error->failure($modx->lexicon('document_err_delete_container_siteunavailable',array('id' => $child->get('id'))));
            }

            $ar_children[] = $child;

            /* recursively loop through tree */
            getChildren($child, $modx, $ar_children);
        }
    }
}

/* prepare children ids for invokeEvents */
$ar_children_ids = array ();
foreach ($ar_children as $child) {
    $ar_children_ids[] = $child->get('id');
}

/* invoke OnBeforeDocFormDelete event */
$modx->invokeEvent('OnBeforeDocFormDelete', array (
    'id' => $resource->get('id'),
    'children' => $ar_children_ids,

));

/* delete children */
if (count($ar_children) > 0) {
    foreach ($ar_children as $child) {
        $child->set('deleted', 1);
        $child->set('deletedby', $modx->user->get('id'));
        $child->set('deletedon', $deltime);
        if ($child->save() == false) {
            return $modx->error->failure($modx->lexicon('resource_err_delete_children'));
        }
    }
}

/* delete the document. */
$resource->set('deleted', 1);
$resource->set('deletedby', $modx->user->get('id'));
$resource->set('deletedon', $deltime);
if ($resource->save() == false) {
    return $modx->error->failure($modx->lexicon('resource_err_delete'));
}

/* invoke OnDocFormDelete event */
$modx->invokeEvent('OnDocFormDelete', array (
    'id' => $resource->get('id'),
    'children' => $ar_children_ids,

));

/* log manager action */
$modx->logManagerAction('delete_resource','modDocument',$resource->get('id'));

/* empty cache */
$cacheManager = $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success('', $resource->get(array (
    'id',
    'deleted',
    'deletedby',
    'deletedon'
)));