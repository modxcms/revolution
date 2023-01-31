<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Resource;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modResource;
use MODX\Revolution\modUser;
use MODX\Revolution\modX;

/**
 * Deletes a resource.
 *
 * @param int $id The ID of the resource
 */
class Delete extends Processor
{
    use ActionAccessTrait;

    /** @var modResource $resource */
    public $resource;
    /** @var modUser $lockedUser */
    public $lockedUser;
    /** @var array $children */
    public $children = [];
    /** @var int $deletedTime */
    public $deletedTime = 0;

    public $permission = 'delete_document';

    public function getLanguageTopics(): array
    {
        return ['resource'];
    }

    /**
     * Get the Resource and check for proper permissions
     *
     * {@inheritDoc}
     * @return boolean|string
     */
    public function initialize()
    {
        $id = $this->getProperty('id', false);
        if (empty($id)) return $this->modx->lexicon('resource_err_ns');
        $this->resource = $this->modx->getObject(modResource::class, $id);
        if (empty($this->resource)) return $this->modx->lexicon('resource_err_nfs', ['id' => $id]);

        /* validate resource can be deleted */
        if (!$this->checkActionPermission($this->resource->get('class_key'), 'delete')
            || !$this->resource->checkPolicy(['save' => true, 'delete' => true])) {
            return $this->modx->lexicon('permission_denied');
        }
        $this->deletedTime = time();
        return true;
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        if ($this->isSitePage('site_start')) {
            return $this->failure($this->modx->lexicon('resource_err_delete_sitestart'));
        }

        if ($this->isSitePage('error_page')) {
            return $this->failure($this->modx->lexicon('resource_err_delete_errorpage'));
        }

        if ($this->isSitePage('site_unavailable_page')) {
            return $this->failure($this->modx->lexicon('resource_err_delete_siteunavailable'));
        }

        /* check for locks on resource */
        if (!$this->addLock()) {
            return $this->failure($this->modx->lexicon('resource_locked_by', ['id' => $this->resource->get('id'), 'user' => $this->lockedUser->get('username')]));
        }

        $childrenIds = $this->getChildrenIds();

        $this->fireBeforeDelete($childrenIds);

        /* delete children */
        $this->deleteChildren();

        /* delete the document. */
        $this->resource->set('deleted', true);
        $this->resource->set('deletedby', $this->modx->user->get('id'));
        $this->resource->set('deletedon', $this->deletedTime);
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

        $deletedCount = $this->modx->getCount(modResource::class, ['deleted' => 1]);

        $outputArray = $this->resource->get([
            'id',
            'deleted',
            'deletedby',
            'deletedon'
        ]);

        $outputArray['deletedCount'] = $deletedCount;

        return $this->success('', $outputArray);
    }

    /**
     * Checks if the given resource is set as page specified in the system settings
     * @return bool
     */
    public function isSitePage(string $option)
    {
        $workingContext = $this->modx->getContext($this->getProperty('context_key', $this->resource->get('context_key') ? $this->resource->get('context_key') : 'web'));
        return ($this->resource->get('id') == $workingContext->getOption($option) || $this->resource->get('id') == $this->modx->getOption($option));
    }

    /**
     * Attempt to add a lock to the Resource
     *
     * @return boolean
     */
    public function addLock()
    {
        $locked = $this->resource->addLock();
        if ($locked !== true) {
            $this->lockedUser = $this->modx->getObject(modUser::class, $locked);
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
    public function getChildrenIds()
    {
        $this->children = [];
        $this->getChildren($this->resource);

        /* prepare children ids for invokeEvents */
        $childrenIds = [];
        /** @var modResource $child */
        foreach ($this->children as $child) {
            $childrenIds[] = $child->get('id');
        }
        return $childrenIds;
    }

    /**
     * Helper method for getChildrenIds for getting Children recursively
     *
     * @param modResource $parent
     * @return void
     * @see getChildrenIds
     */
    protected function getChildren(modResource $parent)
    {
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
    public function fireBeforeDelete(array $childrenIds = [])
    {
        $this->modx->invokeEvent('OnBeforeDocFormDelete', [
            'id' => $this->resource->get('id'),
            'resource' => &$this->resource,
            'children' => $childrenIds,
        ]);
    }

    /**
     * Delete all children of this resource
     * @return array
     */
    public function deleteChildren()
    {
        if (count($this->children) > 0) {
            /** @var modResource $child */
            foreach ($this->children as $child) {
                $locked = $child->addLock();
                if ($locked !== true) {
                    /** @var modUser $user */
                    $user = $this->modx->getObject(modUser::class, $locked);
                    if ($user) {
                        $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('resource_locked_by', ['id' => $child->get('id'), 'user' => $user->get('username')]));
                    }
                }
                $child->set('deleted', true);
                $child->set('deletedby', $this->modx->user->get('id'));
                $child->set('deletedon', $this->deletedTime);
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
    public function fireAfterDelete(array $childrenIds = [])
    {
        $this->modx->invokeEvent('OnDocFormDelete', [
            'id' => $this->resource->get('id'),
            'children' => $childrenIds,
            'resource' => &$this->resource,
        ]);
        $this->modx->invokeEvent('OnResourceDelete', [
            'id' => $this->resource->get('id'),
            'children' => &$childrenIds,
            'resource' => &$this->resource,
        ]);
    }

    /**
     * Clear the site cache
     * @return void
     */
    public function clearCache()
    {
        $clear = $this->getProperty('syncsite', $this->modx->getOption('syncsite_default')) || $this->getProperty('clearCache', false);
        if ($clear) {
            $this->modx->cacheManager->refresh([
                'db' => [],
                'auto_publish' => ['contexts' => [$this->resource->get('context_key')]],
                'context_settings' => ['contexts' => [$this->resource->get('context_key')]],
                'resource' => ['contexts' => [$this->resource->get('context_key')]],
            ]);
        }
    }

    /**
     * Log the manager action
     *
     * @return void
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction('delete_resource', $this->resource->get('class_key'), $this->resource->get('id'));
    }
}
