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
if (!$modx->hasPermission('publish_document')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource');

/* get resource */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('resource_err_ns'));
$resource = $modx->getObject('modResource',$scriptProperties['id']);
if (empty($resource)) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $scriptProperties['id'])));

/* check permissions on the resource */
if (!$resource->checkPolicy(array('save'=>true, 'publish'=>true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

$locked = $resource->addLock();
if ($locked !== true) {
    $user = $modx->getObject('modUser', $locked);
    if ($user) {
        return $modx->error->failure($modx->lexicon('resource_locked_by', array('id' => $resource->get('id'), 'user' => $user->get('username'))));
    }
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
    'id' => $resource->get('id'),
    'resource' => &$resource,
));

/* empty the cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

/* log manager action */
$modx->logManagerAction('publish_resource','modResource',$resource->get('id'));

return $modx->error->success('',$resource->get(array('id', 'pub_date', 'unpub_date', 'editedby', 'editedon', 'publishedby', 'publishedon')));