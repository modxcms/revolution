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

$_DATA = $modx->fromJSON($scriptProperties['data']);

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


/* set publishedon date if publish change is different */
if (isset($_DATA['published']) && $_DATA['published'] != $resource->get('published')) {
    if (empty($_DATA['published'])) { /* if unpublishing */
        $_DATA['publishedon'] = 0;
        $_DATA['publishedby'] = 0;
    } else { /* if publishing */
        $_DATA['publishedon'] = !empty($_DATA['publishedon']) ? strtotime($_DATA['publishedon']) : time();
        $_DATA['publishedby'] = $modx->user->get('id');
    }
} else { /* if no change, unset publishedon/publishedby */
    if (empty($_DATA['published'])) { /* allow changing of publishedon date if resource is published */
        unset($_DATA['publishedon']);
    }
    unset($_DATA['publishedby']);
}
/* Keep original publish state, if change is not permitted */
if (!$modx->hasPermission('publish_document')) {
    $_DATA['publishedon'] = $resource->get('publishedon');
    $_DATA['publishedby'] = $resource->get('publishedby');
    $_DATA['pub_date'] = $resource->get('pub_date');
    $_DATA['unpub_date'] = $resource->get('unpub_date');
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
