<?php
/**
 * Release a lock on a resource
 *
 * @package modx
 * @subpackage processors.resource.locks
 */
class modResourceLocksReleaseProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('view');
    }
    public function process() {
        $released = false;
        $id = $this->getProperty('id');
        $id = intval($id);

        if (!empty($id)) {
            /** @var modResource $resource */
            $resource = $this->modx->getObject('modResource',$id);
            if ($resource) {
                $released = $resource->removeLock($this->modx->user->get('id'));
            }
        }

        if (!$released) {
            $this->failure();
        }

        return $this->success();
    }
}
return 'modResourceLocksReleaseProcessor';
