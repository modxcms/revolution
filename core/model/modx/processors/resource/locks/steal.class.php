<?php
/**
 * Steal a lock on a resource
 *
 * @package modx
 * @subpackage processors.resource.locks
 */
class modResourceLocksStealProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('steal_locks');
    }

    public function process() {
        $stolen = false;
        if (!empty($scriptProperties['id'])) {
            /** @var modResource $resource */
            $resource = $this->modx->getObject('modResource', intval($scriptProperties['id']));
            if ($resource && $resource->checkPolicy('steal_lock')) {
                $lock = $resource->getLock($this->modx->user->get('id'));
                if ($lock > 0 && $lock != $this->modx->user->get('id')) {
                    $resource->removeLock($lock);
                    $stolen = $resource->addLock($this->modx->user->get('id'));
                }
            }
        }
        if ($stolen !== true) return $this->failure($stolen);

        return $this->success();
    }
}
return 'modResourceLocksStealProcessor';