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
 * Restores deleted files.
 *
 * @return boolean
 * @package    modx
 * @subpackage processors.resource
 */
class modResourceTrashRestoreProcessor extends modProcessor {

    /** @var modResource[] $resources */
    private $resources;

    /** @var array $failures Failed ids of restored resources */
    private $failures;

    /** @var array $success Ids of successfully restored resources */
    private $success;

    public function checkPermissions()
    {
        return $this->modx->hasPermission('undelete_document');
    }

    public function getLanguageTopics()
    {
        return array('resource', 'trash');
    }

    /**
     * @return bool|null|string
     */
    public function initialize() {
        // we expect a list of ids here
        $idlist = $this->getProperty('ids', false);

        if (!$idlist) {
            return $this->modx->lexicon('resource_err_ns');
        }

        $idlist = explode(',', $idlist);

        if (count($idlist) === 0) {
            return $this->modx->lexicon('resource_err_ns');
        }

        // and now retrieve a collection of resources here
        $this->resources = $this->modx->getCollection('modResource', array(
            'deleted' => true,
            'id:IN' => $idlist,
        ));
        $count = count($this->resources);

        if ($count === 0) {
            return $this->modx->lexicon('resource_err_nfs', array('ids' => $idlist));
        }

        return true;
    }

    public function process() {
        $this->success = array();
        $contexts = array();

        foreach ($this->resources as $resource) {
            $contexts[] = $resource->get('context_key');

            $id = $resource->get('id');

            if (!$this->addLock($resource)) {
                $lockedUser = $this->modx->getObject('modUser', $resource->getLock());
                return $this->failure($this->modx->lexicon('resource_locked_by', array(
                    'id' => $resource->get('id'),
                    'user' => ($lockedUser) ? $lockedUser->get('username') : '(unknown)')
                ));
            }

            /* 'undelete' the resource. */
            $resource->set('deleted', false);
            $resource->set('deletedby', 0);
            $resource->set('deletedon', 0);

            if (!$resource->save()) {
                $resource->removeLock($resource);

                $this->failures[] = $id;

                return $this->failure($this->modx->lexicon('resource_err_undelete'));
            } else {
                $this->success[] = $id;
            }

            $this->fireAfterUnDeleteEvent($resource);

            /* log manager action */
            $this->logManagerAction($resource);
            $this->removeLock($resource);
        }
        /* empty cache */
        $this->clearCache($contexts);

        $outputArray = $resource->get(array('id'));

        $outputArray['successes'] = $this->success;
        $outputArray['failures'] = $this->failures;

        $msg = '';
        if ($outputArray['successes'] > 0) {
            $msg = $this->modx->lexicon('trash.restore_success', array(
                'list' => implode(', ', $this->success),
                'count_success' => count($this->success),
            ));

            if (count($this->failures) > 0) {
                $msg .= '<br/>' . $this->modx->lexicon('trash.restore_err', array(
                        'list' => implode(', ', $this->failures),
                        'count_failures' => count($this->failures)
                    ));
            }
        }

        return $this->modx->error->success($msg, array(
            'count_success' => count($this->success),
            'count_failures' => count($this->failures),
        ));
    }

    /**
     * Add a lock to the Resource while undeleting it
     *
     * @param modResource $resource
     * @return boolean
     */
    public function addLock($resource) {
        $locked = $resource->addLock();
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
     *
     * @param modResource $resource
     * @return boolean
     */
    public function removeLock($resource) {
        return $resource->removeLock();
    }

    /**
     * Fire the UnDelete event
     *
     * @param modResource $resource
     * @return void
     */
    public function fireAfterUnDeleteEvent($resource) {
        $this->modx->invokeEvent('OnResourceUndelete', array(
            'id' => $resource->get('id'),
            'resource' => &$resource,
        ));
    }

    /**
     * Log the manager action
     *
     * @param modResource $resource
     * @return void
     */
    public function logManagerAction($resource) {
        $this->modx->logManagerAction('undelete_resource', 'modResource', $resource->get('id'));
    }

    /**
     * Clear the site cache for the restored resources contexts
     *
     * @return void
     */
    public function clearCache($contexts) {
        $this->modx->cacheManager->refresh(array(
            'db' => array(),
            'auto_publish' => array('contexts' => $contexts),
            'context_settings' => array('contexts' => $contexts),
            'resource' => array('contexts' => $contexts),
        ));
    }
}

return 'modResourceTrashRestoreProcessor';
