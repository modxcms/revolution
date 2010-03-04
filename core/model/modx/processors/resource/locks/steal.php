<?php
/**
 * Steal a lock on a resource
 *
 * @package modx
 * @subpackage processors.resource.locks
 */
if (!$modx->hasPermission('steal_locks')) return $modx->error->failure($modx->lexicon('permission_denied'));

$stolen = false;
if (!empty($scriptProperties['id'])) {
    $resource = $modx->getObject('modResource', intval($scriptProperties['id']));
    if ($resource && $resource->checkPolicy('steal_lock')) {
        $lock = $resource->getLock($modx->user->get('id'));
        if ($lock > 0 && $lock != $modx->user->get('id')) {
            $resource->removeLock($lock);
            $stolen = $resource->addLock($modx->user->get('id'));
        }
    }
}
if ($stolen !== true) return $modx->error->failure($stolen);

return $modx->error->success('');