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

/**
 * Undeletes a resource.
 *
 * @param integer $id The ID of the resource
 * @return array An array with the ID of the undeleted resource
 */
class Undelete extends Processor
{
    /** @var modResource $resource */
    public $resource;
    /** @var modUser $user */
    public $lockedUser;

    public function checkPermissions()
    {
        return $this->modx->hasPermission('undelete_document');
    }

    public function getLanguageTopics()
    {
        return ['resource'];
    }

    public function initialize()
    {
        $id = $this->getProperty('id', false);
        if (empty($id)) return $this->modx->lexicon('resource_err_ns');
        $this->resource = $this->modx->getObject(modResource::class, $id);
        if (empty($this->resource)) return $this->modx->lexicon('resource_err_nfs', ['id' => $id]);

        /* check permissions on the resource */
        if (!$this->resource->checkPolicy(['save' => 1, 'undelete' => 1])) {
            return $this->modx->lexicon('permission_denied');
        }
        return true;
    }

    public function process()
    {
        if (!$this->addLock()) {
            return $this->failure($this->modx->lexicon('resource_locked_by', ['id' => $this->resource->get('id'), 'user' => $this->lockedUser->get('username')]));
        }

        /* 'undelete' the resource. */
        $this->resource->set('deleted', false);
        $this->resource->set('deletedby', 0);
        $this->resource->set('deletedon', 0);

        if ($this->resource->save() == false) {
            $this->resource->removeLock();
            return $this->failure($this->modx->lexicon('resource_err_undelete'));
        }

        $this->unDeleteChildren($this->resource->get('id'), $this->resource->get('deletedon'));

        $this->fireAfterUnDeleteEvent();

        /* log manager action */
        $this->logManagerAction();

        /* empty cache */
        $this->clearCache();
        $this->removeLock();

        $deletedCount = $this->modx->getCount(modResource::class, ['deleted' => 1]);

        $outputArray = $this->resource->get(['id']);

        $outputArray['deletedCount'] = $deletedCount;

        return $this->modx->error->success('', $outputArray);
    }

    /**
     * Add a lock to the Resource while undeleting it
     * @return boolean
     */
    public function addLock()
    {
        $locked = $this->resource->addLock();
        if ($locked !== true) {
            $user = $this->modx->getObject(modUser::class, $locked);
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
    public function removeLock()
    {
        return $this->resource->removeLock();
    }

    /**
     * UnDelete all the children Resources recursively
     * @param int $parent
     * @return boolean
     */
    public function unDeleteChildren($parent)
    {
        $success = false;

        $kids = $this->modx->getCollection(modResource::class, [
            'parent' => $parent,
            'deleted' => true,
        ]);

        if (count($kids) > 0) {
            /* the resource has children resources, we'll need to undelete those too */
            /** @var modResource $kid */
            foreach ($kids as $kid) {
                $locked = $kid->addLock();
                if ($locked !== true) {
                    $user = $this->modx->getObject(modUser::class, $locked);
                    if ($user) {
                        continue;
                    }
                }
                $kid->set('deleted', 0);
                $kid->set('deletedby', 0);
                $kid->set('deletedon', 0);
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
    public function fireAfterUnDeleteEvent()
    {
        $this->modx->invokeEvent('OnResourceUndelete', [
            'id' => $this->resource->get('id'),
            'resource' => &$this->resource,
        ]);
    }

    /**
     * Log the manager action
     * @return void
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction('undelete_resource', modResource::class, $this->resource->get('id'));
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
}
