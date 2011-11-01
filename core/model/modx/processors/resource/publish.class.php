<?php
/**
 * Publishes a resource.
 *
 * @param integer $id The ID of the resource
 *
 * @package modx
 * @subpackage processors.resource
 */
class modResourcePublishProcessor extends modProcessor {
    /** @var modResource $resource */
    public $resource;
    /** @var modUser $lockedUser */
    public $lockedUser;
    
    public function checkPermissions() {
        return $this->modx->hasPermission('publish_document');
    }
    public function getLanguageTopics() {
        return array('resource');
    }
    public function initialize() {
        $id = $this->getProperty('id',false);
        if (empty($id)) return $this->modx->lexicon('resource_err_ns');
        $this->resource = $this->modx->getObject('modResource', $id);
        if (empty($this->resource)) return $this->modx->lexicon('resource_err_nfs',array('id' => $id));

        /* validate resource can be deleted */
        if (!$this->resource->checkPolicy(array('save' => true, 'publish' => true))) {
            return $this->modx->lexicon('permission_denied');
        }
        return true;
    }

    public function process() {
        if (!$this->addLock()) {
            return $this->failure($this->modx->lexicon('resource_locked_by', array(
                'id' => $this->resource->get('id'),
                'user' => $this->lockedUser->get('username'),
            )));
        }

        $duplicateAlias = $this->checkForDuplicateAlias();
        if ($duplicateAlias !== false) {
            return $this->failure($duplicateAlias);
        }
        
        /* publish resource */
        $this->resource->set('published',true);
        $this->resource->set('pub_date',false);
        $this->resource->set('unpub_date',false);
        $this->resource->set('editedby',$this->modx->user->get('id'));
        $this->resource->set('editedon',time(),'integer');
        $this->resource->set('publishedby',$this->modx->user->get('id'));
        $this->resource->set('publishedon',time());
        $saved = $this->resource->save();
        $this->resource->removeLock();
        if (!$saved) return $this->failure($this->modx->lexicon('resource_err_publish'));

        $this->fireAfterPublish();
        $this->logManagerAction();
        $this->clearCache();
        return $this->success('',$this->resource->get(array('id', 'pub_date', 'unpub_date', 'editedby', 'editedon', 'publishedby', 'publishedon')));
    }

    /**
     * Attempt to lock the Resource
     * @return boolean
     */
    public function addLock() {
        $locked = $this->resource->addLock();
        if ($locked !== true) {
            $this->lockedUser = $this->modx->getObject('modUser', $locked);
            if ($this->lockedUser) {
                $locked = false;
            }
        }
        return $locked;
    }

    /**
     * Check for a duplicate alias before publishing
     * @return boolean|string
     */
    public function checkForDuplicateAlias() {
        $duplicateAlias = false;

        /* get the targeted working context */
        $workingContext = $this->modx->getContext($this->resource->get('context_key'));

        /* friendly url duplicate alias checks */
        if ($workingContext->getOption('friendly_urls', false)) {
            $duplicateContext = $workingContext->getOption('global_duplicate_uri_check', false) ? '' : $this->resource->get('context_key');
            $aliasPath = $this->resource->getAliasPath($this->resource->get('alias'));
            $duplicateId = $this->resource->isDuplicateAlias($aliasPath, $duplicateContext);
            if (!empty($duplicateId)) {
                return $this->modx->lexicon('duplicate_uri_found', array('id' => $duplicateId, 'uri' => $aliasPath));
            }
        }
        return $duplicateAlias;
    }

    /**
     * Fire after-publish events
     * @return void
     */
    public function fireAfterPublish() {
        $this->modx->invokeEvent('OnDocPublished',array(
            'docid' => $this->resource->get('id'),
            'id' => $this->resource->get('id'),
            'resource' => &$this->resource,
        ));
    }

    /**
     * Log a manager action
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('publish_resource',$this->resource->get('class_key'),$this->resource->get('id'));
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
return 'modResourcePublishProcessor';