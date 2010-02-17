<?php
/**
 *
 * @param $data A JSON array of data to update from.
 *
 * @package modx
 * @subpackage processors.resource
 */
if (!$modx->hasPermission('save_document')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource');

$_DATA = $modx->fromJSON($_POST['data']);

/* get resource */
if (empty($_DATA['id'])) return $modx->error->failure($modx->lexicon('resource_err_ns'));
$resource = $modx->getObject('modResource',$_DATA['id']);
if (empty($resource)) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $_DATA['id'])));

/* check policy on resource */
if (!$resource->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

/* check for locks */
$locked = $resource->addLock();
if ($locked !== true) {
    $user = $modx->getObject('modUser', $locked);
    if ($user) {
        return $modx->error->failure($modx->lexicon('resource_locked_by', array('id' => $resource->get('id'), 'user' => $user->get('username'))));
    }
}

/* save resource */
$resource->fromArray($_DATA);
if ($resource->save() === false) {
    $resource->removeLock();
    return $modx->error->failure($modx->lexicon('resource_err_save'));
}

$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache(array (
        "{$resource->context_key}/resources/",
        "{$resource->context_key}/context.cache.php",
    ),
    array(
        'objects' => array('modResource', 'modContext', 'modTemplateVarResource'),
        'publishing' => true
    )
);

$resource->removeLock();

return $modx->error->success();
