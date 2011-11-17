<?php
/**
 * Unpublishes a resource.
 *
 * @param integer $id The ID of the resource
 * @return array An array with the ID of the unpublished resource
 *
 * @package modx
 * @subpackage processors.resource
 */
class modResourceUnPublishProcessor extends modProcessor {
    /** @var modResource $resource */
    public $resource;
    /** @var modUser $user */
    public $lockedUser;
    
    public function checkPermissions() {
        return $this->modx->hasPermission('unpublish_document');
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
        if (!$this->resource->checkPolicy(array('save'=>1, 'unpublish'=>1))) {
            return $this->modx->lexicon('permission_denied');
        }
        return true;
    }

    public function process() {
        if (!$this->addLock()) {
            return $this->failure($this->modx->lexicon('resource_locked_by', array('id' => $this->resource->get('id'), 'user' => $this->lockedUser->get('username'))));
        }

        $this->resource->set('published',false);
        $this->resource->set('pub_date',false);
        $this->resource->set('unpub_date',false);
        $this->resource->set('editedby',$this->modx->user->get('id'));
        $this->resource->set('editedon',time(),'integer');
        $this->resource->set('publishedby',false);
        $this->resource->set('publishedon',false);
        if ($this->resource->save() == false) {
            $this->resource->removeLock();
            return $this->failure($this->modx->lexicon('resource_err_unpublish'));
        }

        $this->fireAfterUnPublishEvent();
        $this->logManagerAction();
        $this->clearCache();
        return $this->success('',$this->resource->get(array('id')));
    }

    /**
     * Add a lock to the Resource while unpublishing it
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
     * Fire the after unpublish event
     * @return void
     */
    public function fireAfterUnPublishEvent() {
        $this->modx->invokeEvent('OnDocUnPublished',array(
            'docid' => $this->resource->get('id'),
            'id' => $this->resource->get('id'),
            'resource' => &$this->resource,
        ));
    }

    /**
     * Log the manager action
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('unpublish_resource','modResource',$this->resource->get('id'));
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
return 'modResourceUnPublishProcessor';
