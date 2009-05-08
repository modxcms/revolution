<?php
/**
 * Releases a lock on a resource
 *
 * @package modx
 * @subpackage processors.resource.locks
 */

if (isset($_POST['id'])) {
    $resource = $modx->getObject('modResource', intval($_POST['id']));
    if ($resource) {
        $resource->removeLock($modx->user->get('id'));
    }
}

return $modx->error->success($resource->get('id'));