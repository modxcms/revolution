<?php
/**
 * Undeletes a resource.
 *
 * @param integer $id The ID of the resource
 * @return array An array with the ID of the undeleted resource
 *
 * @package modx
 * @subpackage processors.resource
 */
class modResourceUnDeleteProcessor extends modProcessor {
    /** @var modResource $resource */
    public $resource;
    /** @var modUser $user */
    public $lockedUser;
    
    public function checkPermissions() {
        return $this->modx->hasPermission('undelete_document');
    }
    public function getLanguageTopics() {
        return array('resource');
    }
    
    public function initialize() {
        $id = $this->getProperty('id',false);
        if (empty($id)) return $this->modx->lexicon('resource_err_ns');
        $this->resource = $this->modx->getObject('modResource',$id);
        if (empty($this->resource)) return $this->modx->lexicon('resource_err_nfs',array('id' => $id));

        /* check permissions on the resource */
        if (!$this->resource->checkPolicy(array('save'=>1, 'undelete'=>1))) {
            return $this->modx->lexicon('permission_denied');
        }
        return true;
    }

    public function process() {
        if (!$this->addLock()) {
            return $this->failure($this->modx->lexicon('resource_locked_by', array('id' => $this->resource->get('id'), 'user' => $this->lockedUser->get('username'))));
        }

        /* 'undelete' the resource. */
        $this->resource->set('deleted',false);
        $this->resource->set('deletedby',0);
        $this->resource->set('deletedon',0);

        if ($this->resource->save() == false) {
            $this->resource->removeLock();
            return $this->failure($this->modx->lexicon('resource_err_undelete'));
        }

        $this->unDeleteChildren($this->resource->get('id'),$this->resource->get('deletedon'));

        $this->fireAfterUnDeleteEvent();

        /* log manager action */
        $this->logManagerAction();

        /* empty cache */
        $this->clearCache();
        $this->removeLock();

        $deletedCount = $this->modx->getCount('modResource', array('deleted' => 1));

        $outputArray = $this->resource->get(array('id'));

        $outputArray['deletedCount'] = $deletedCount;

        return $this->modx->error->success('', $outputArray);
    }

    /**
     * Add a lock to the Resource while undeleting it
     * @return boolean
     */
    public function addLock() {
        $locked = $this->resource->addLock();
        if ($locked !== true) {
            $user = $this->modx->getObject('modUser', $locked);
            if ($user) {
                $locked = false;
            }
        }
        return $locked;
    }

    /**
     * Remove the lock from the Resource
     * @return boolean
     */
    public function removeLock() {
        return $this->resource->removeLock();
    }

    /**
     * UnDelete all the children Resources recursively
     * @param int $parent
     * @return boolean
     */
    public function unDeleteChildren($parent) {
        $success = false;

        $kids = $this->modx->getCollection('modResource',array(
            'parent' => $parent,
            'deleted' => true,
        ));

        if(count($kids) > 0) {
            /* the resource has children resources, we'll need to undelete those too */
            /** @var modResource $kid */
            foreach ($kids as $kid) {
                $locked = $kid->addLock();
                if ($locked !== true) {
                    $user = $this->modx->getObject('modUser', $locked);
                    if ($user) {
                        continue;
                    }
                }
                $kid->set('deleted',0);
                $kid->set('deletedby',0);
                $kid->set('deletedon',0);
                $success = $kid->save();
                if ($success) {
                    $success = $this->unDeleteChildren($kid->get('id'));
                }
            }
        }
        return $success;
    }

    /**
     * Fire the UnDelete event
     * @return void
     */
    public function fireAfterUnDeleteEvent() {
        $this->modx->invokeEvent('OnResourceUndelete',array(
            'id' => $this->resource->get('id'),
            'resource' => &$this->resource,
        ));
    }

    /**
     * Log the manager action
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('undelete_resource','modResource',$this->resource->get('id'));
    }

    /**
     * Clear the site cache
     * @return void
     */
    public function clearCache() {
        $this->modx->cacheManager->refresh(array(
            'db' => array(),
            'auto_publish' => array('contexts' => array($this->resource->get('context_key'))),
            'context_settings' => array('contexts' => array($this->resource->get('context_key'))),
            'resource' => array('contexts' => array($this->resource->get('context_key'))),
        ));
    }
}
return 'modResourceUnDeleteProcessor';