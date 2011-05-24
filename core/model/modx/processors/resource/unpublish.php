<?php
/**
 * Unpublishes a resource.
 *
 * @param integer $id The ID of the resource
 * @return array An array with the ID of the unpublished resource
 *
 * @package modx
 * @subpackage processors.resource
 */
if (!$modx->hasPermission('unpublish_document')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource');

if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('resource_err_ns'));
$resource = $modx->getObject('modResource',$scriptProperties['id']);
if (empty($resource)) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $scriptProperties['id'])));

/* check permissions on the resource */
if (!$resource->checkPolicy(array('save'=>true, 'unpublish'=>true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

$locked = $resource->addLock();
if ($locked !== true) {
    $user = $modx->getObject('modUser', $locked);
    if ($user) {
        return $modx->error->failure($modx->lexicon('resource_locked_by', array('id' => $resource->get('id'), 'user' => $user->get('username'))));
    }
}

/* update the resource */
$resource->set('published',false);
$resource->set('pub_date',false);
$resource->set('unpub_date',false);
$resource->set('editedby',$modx->user->get('id'));
$resource->set('editedon',time(),'integer');
$resource->set('publishedby',false);
$resource->set('publishedon',false);
if ($resource->save() == false) {
    $resource->removeLock();
    return $modx->error->failure($modx->lexicon('resource_err_unpublish'));
}

/* invoke OnDocUnpublished event */
$modx->invokeEvent('OnDocUnPublished',array(
    'docid' => $resource->get('id'),
    'id' => $resource->get('id'),
    'resource' => &$resource,
));

/* log manager action */
$modx->logManagerAction('unpublish_resource','modResource',$resource->get('id'));

/* empty cache */
$modx->cacheManager->refresh(array(
    'db' => array(),
    'auto_publish' => array('contexts' => array($resource->get('context_key'))),
    'context_settings' => array('contexts' => array($resource->get('context_key'))),
    'resource' => array('contexts' => array($resource->get('context_key'))),
));

return $modx->error->success('',$resource->get(array('id')));