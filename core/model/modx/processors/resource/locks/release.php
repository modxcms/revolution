<?php
/**
 * Release a lock on a resource
 *
 * @package modx
 * @subpackage processors.resource.locks
 */

$released = false;
if (isset($_POST['id'])) {
    $resource = $modx->getObject('modResource', intval($_POST['id']));
    if ($resource) {
        $released = $resource->removeLock($modx->user->get('id'));
    }
}

return $modx->error->success();