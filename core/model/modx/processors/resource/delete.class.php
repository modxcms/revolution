<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Deletes a resource.
 *
 * @param integer $id The ID of the resource
 *
 * @package modx
 * @subpackage processors.resource
 */
class modResourceDeleteProcessor extends modProcessor {
    /** @var modResource $resource */
    public $resource;
    /** @var modUser $lockedUser */
    public $lockedUser;
    /** @var array $children */
    public $children = array();
    /** @var int $deletedTime */
    public $deletedTime = 0;
    public $permission = 'delete_document';

    public function getLanguageTopics() {
        return array('resource');
    }
    /**
     * Get the Resource and check for proper permissions
     *
     * {@inheritDoc}
     * @return boolean|string
     */
    public function initialize() {
        $id = $this->getProperty('id',false);
        if (empty($id)) return $this->modx->lexicon('resource_err_ns');
        $this->resource = $this->modx->getObject('modResource', $id);
        if (empty($this->resource)) return $this->modx->lexicon('resource_err_nfs',array('id' => $id));

        /* validate resource can be deleted */
        if (!$this->checkDeletePermission() || !$this->resource->checkPolicy(array('save' => true, 'delete' => true))) {
            return $this->modx->lexicon('permission_denied');
        }
        $this->deletedTime = time();
        return true;
    }

    public function checkDeletePermission()
    {
        $permissions = [$this->permission];
        $classKey = $this->resource->get('class_key');

        $map = [
            'modWebLink' => 'delete_weblink',
            'modSymLink' => 'delete_symlink',
            'modStaticResource' => 'delete_resource',
        ];
        if (array_key_exists($classKey, $map)) {
            $permissions[] = $map[$classKey];
        }

        foreach ($permissions as $permission) {
            if (!$this->modx->hasPermission($permission)) {
                // allow the error message shown to the user (in modProcessor->run()) to show the right failed permission
                $this->permission = $permission;
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process() {
        if ($this->modx->getOption('site_start') == $this->resource->get('id')) {
            return $this->failure($this->modx->lexicon('resource_err_delete_sitestart'));
        }
        if ($this->modx->getOption('site_unavailable_page') == $this->resource->get('id')) {
            return $this->failure($this->modx->lexicon('resource_err_delete_siteunavailable'));
        }

        /* check for locks on resource */
        if (!$this->addLock()) {
            return $this->failure($this->modx->lexicon('resource_locked_by', array('id' => $this->resource->get('id'), 'user' => $this->lockedUser->get('username'))));
        }

        $childrenIds = $this->getChildrenIds();

        $this->fireBeforeDelete($childrenIds);

        /* delete children */
        $this->deleteChildren();

        /* delete the document. */
        $this->resource->set('deleted',true);
        $this->resource->set('deletedby',$this->modx->user->get('id'));
        $this->resource->set('deletedon',$this->deletedTime);
        if ($this->resource->save() == false) {
            $this->resource->removeLock();
            return $this->failure($this->modx->lexicon('resource_err_delete'));
        }

        $this->fireAfterDelete($childrenIds);

        /* log manager action */
        $this->logManagerAction();

        $this->resource->removeLock();

        /* empty cache */
        $this->clearCache();

        $deletedCount = $this->modx->getCount('modResource', array('deleted' => 1));

        $outputArray = $this->resource->get(array (
            'id',
            'deleted',
            'deletedby',
            'deletedon'
        ));

        $outputArray['deletedCount'] = $deletedCount;

        return $this->success('', $outputArray);
    }

    /**
     * Attempt to add a lock to the Resource
     *
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
     * Get the IDs of all the children of the Resource
     * @return array
     */
    public function getChildrenIds() {
        $this->children = array();
        $this->getChildren($this->resource);

        /* prepare children ids for invokeEvents */
        $childrenIds = array ();
        /** @var modResource $child */
        foreach ($this->children as $child) {
            $childrenIds[] = $child->get('id');
        }
        return $childrenIds;
    }

    /**
     * Helper method for getChildrenIds for getting Children recursively
     *
     * @see getChildrenIds
     * @param modResource $parent
     * @return void
     */
    protected function getChildren(modResource $parent) {
        $childResources = $parent->getMany('Children');
        if (count($childResources) > 0) {
            /** @var modResource $child */
            foreach ($childResources as $child) {
                if ($child->get('id') == $this->modx->getOption('site_start')) {
                    continue;
                }
                if ($child->get('id') == $this->modx->getOption('site_unavailable_page')) {
                    continue;
                }

                $this->children[] = $child;

                /* recursively loop through tree */
                $this->getChildren($child);
            }
        }
    }

    /**
     * Fire any pre-delete events
     * @param array $childrenIds
     * @return void
     */
    public function fireBeforeDelete(array $childrenIds = array()) {
        $this->modx->invokeEvent('OnBeforeDocFormDelete', array (
            'id' => $this->resource->get('id'),
            'resource' => &$this->resource,
            'children' => $childrenIds,
        ));
    }

    /**
     * Delete all children of this resource
     * @return array
     */
    public function deleteChildren() {
        if (count($this->children) > 0) {
            /** @var modResource $child */
            foreach ($this->children as $child) {
                $locked = $child->addLock();
                if ($locked !== true) {
                    /** @var modUser $user */
                    $user = $this->modx->getObject('modUser', $locked);
                    if ($user) {
                        $this->modx->log(modX::LOG_LEVEL_ERROR,$this->modx->lexicon('resource_locked_by', array('id' => $child->get('id'), 'user' => $user->get('username'))));
                    }
                }
                $child->set('deleted',true);
                $child->set('deletedby',$this->modx->user->get('id'));
                $child->set('deletedon',$this->deletedTime);
                if ($child->save() == false) {
                    $child->removeLock();
                    $this->resource->removeLock();
                }
            }
        }
        return $this->children;
    }

    /**
     * Fire the after-delete events
     * @param array $childrenIds
     * @return void
     */
    public function fireAfterDelete(array $childrenIds = array()) {
        $this->modx->invokeEvent('OnDocFormDelete', array (
            'id' => $this->resource->get('id'),
            'children' => $childrenIds,
            'resource' => &$this->resource,
        ));
        $this->modx->invokeEvent('OnResourceDelete',array(
            'id' => $this->resource->get('id'),
            'children' => &$childrenIds,
            'resource' => &$this->resource,
        ));
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

    /**
     * Log the manager action
     *
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('delete_resource',$this->resource->get('class_key'),$this->resource->get('id'));
    }
}
return 'modResourceDeleteProcessor';
