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
 * Unpublishes a resource.
 *
 * @param integer $id The ID of the resource
 * @return array An array with the ID of the unpublished resource
 */
class Unpublish extends Processor
{
    /** @var modResource $resource */
    public $resource;
    /** @var modUser $user */
    public $lockedUser;

    public function checkPermissions()
    {
        return $this->modx->hasPermission('unpublish_document');
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
        if (!$this->resource->checkPolicy(['save' => 1, 'unpublish' => 1])) {
            return $this->modx->lexicon('permission_denied');
        }
        return true;
    }

    public function process()
    {
        if (!$this->addLock()) {
            return $this->failure($this->modx->lexicon('resource_locked_by', ['id' => $this->resource->get('id'), 'user' => $this->lockedUser->get('username')]));
        }

        if ($this->isSitePage('site_start')) {
            return $this->failure($this->modx->lexicon('resource_err_unpublish_sitestart'));
        }

        if ($this->isSitePage('error_page')) {
            return $this->failure($this->modx->lexicon('resource_err_unpublish_errorpage'));
        }

        if ($this->isSitePage('site_unavailable_page')) {
            return $this->failure($this->modx->lexicon('resource_err_unpublish_siteunavailable'));
        }

        $this->resource->set('published', false);
        $this->resource->set('pub_date', false);
        $this->resource->set('unpub_date', false);
        $this->resource->set('editedby', $this->modx->user->get('id'));
        $this->resource->set('editedon', time(), 'integer');
        $this->resource->set('publishedby', false);
        $this->resource->set('publishedon', false);
        if ($this->resource->save() == false) {
            $this->resource->removeLock();
            return $this->failure($this->modx->lexicon('resource_err_unpublish'));
        }

        $this->fireAfterUnPublishEvent();
        $this->logManagerAction();
        $this->clearCache();
        return $this->success('', $this->resource->get(['id']));
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
     * Add a lock to the Resource while unpublishing it
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
     * Fire the after unpublish event
     * @return void
     */
    public function fireAfterUnPublishEvent()
    {
        $this->modx->invokeEvent('OnDocUnPublished', [
            'docid' => $this->resource->get('id'),
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
        $this->modx->logManagerAction('unpublish_resource', modResource::class, $this->resource->get('id'));
    }

    /**
     * Clear the site cache
     * @return void
     */
    public function clearCache()
    {
        $this->modx->cacheManager->refresh([
            'db' => [],
            'auto_publish' => ['contexts' => [$this->resource->get('context_key')]],
            'context_settings' => ['contexts' => [$this->resource->get('context_key')]],
            'resource' => ['contexts' => [$this->resource->get('context_key')]],
        ]);
    }
}
