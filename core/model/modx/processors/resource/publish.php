<?php
/**
 * Publishes a resource.
 *
 * @param integer $id The ID of the resource
 * @return array
 *
 * @package modx
 * @subpackage processors.resource
 */
$modx->lexicon->load('resource');

/* get resource */
$resource = $modx->getObject('modResource',$_REQUEST['id']);
if ($resource == null) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $_REQUEST['id'])));

if (!$modx->hasPermission('publish_document')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

/* check permissions on the resource */
if (!$resource->checkPolicy(array('save'=>true, 'publish'=>true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

$locked = $resource->addLock();
if ($locked !== true) {
    $user = $modx->getObject('modUser', $locked);
    if ($user) $modx->error->failure($modx->lexicon('resource_locked_by', array('id' => $resource->get('id'), 'user' => $user->get('username'))));
}

/* publish resource */
$resource->set('published',true);
$resource->set('pub_date',false);
$resource->set('unpub_date',false);
$resource->set('editedby',$modx->user->get('id'));
$resource->set('editedon',time(),'integer');
$resource->set('publishedby',$modx->user->get('id'));
$resource->set('publishedon',time());
$saved = $resource->save();

$resource->removeLock();

if (!$saved) return $modx->error->failure($modx->lexicon('resource_err_publish'));

/* invoke OnDocPublished event */
$modx->invokeEvent('OnDocPublished',array(
    'docid' => $resource->get('id'),
    'resource' => $resource->get('id'),
));

/* empty the cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

/* log manager action */
$modx->logManagerAction('publish_resource','modResource',$resource->get('id'));

return $modx->error->success('',$resource->get(array('id', 'pub_date', 'unpub_date', 'editedby', 'editedon', 'publishedby', 'publishedon')));