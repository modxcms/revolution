<?php

/**
 * Restores deleted files.
 *
 * @return boolean
 * @package    modx
 * @subpackage processors.resource
 */
class modResourceTrashRestoreProcessor extends modProcessor {

    /** @var modResource $resource */
    public $resource;

    private $resourceList;
    private $idList;
    private $contexts;

    /** @var array Failed ids of restored resources */
    private $failures;

    /** @var array Ids of successfully restored resources */
    private $success;

    public function checkPermissions() {
        return $this->modx->hasPermission('undelete_document');
    }

    public function getLanguageTopics() {
        return array('resource', 'trash');
    }

    /**
     * @return bool|null|string
     */
    public function initialize() {
        // we expect a list of ids here
        $idlist = $this->getProperty('ids', false);

        $this->contexts = array();

        if (!$idlist) {
            return $this->modx->lexicon('resource_err_ns');
        }

        $this->idList = explode(',', $idlist);

        if (count($this->idList) === 0) {
            return $this->modx->lexicon('resource_err_ns');
        }

        // and now retrieve a collection of resources here
        $this->resourceList = $this->modx->getCollection('modResource', array(
            'deleted' => true,
            'id:IN' => $this->idList,
        ));
        $count = sizeof($this->resourceList);

        if (empty($this->resourceList)) {
            return $this->modx->lexicon('resource_err_nfs', array('ids' => $this->idList));
        }

        return true;
    }

    public function process() {
        $this->success = array();

        foreach ($this->resourceList as $resource) {
            $this->resource = $resource;
            $this->contexts[] = ($this->resource->get('context_key'));

            $id = $resource->get('id');

            if (!$this->addLock()) {
                return $this->failure($this->modx->lexicon('resource_locked_by',
                    array('id' => $this->resource->get('id'), 'user' => $this->lockedUser->get('username'))));
            }

            /* 'undelete' the resource. */
            $this->resource->set('deleted', false);
            $this->resource->set('deletedby', 0);
            $this->resource->set('deletedon', 0);

            if ($this->resource->save() == false) {
                $this->resource->removeLock();

                $this->failures[] = $id;

                return $this->failure($this->modx->lexicon('resource_err_undelete'));
            } else {
                $this->success[] = $id;
            }

            // TODO this still has to be discussed: what happens to the children?
            // $this->unDeleteChildren($this->resource->get('id'), $this->resource->get('deletedon'));

            $this->fireAfterUnDeleteEvent();

            /* log manager action */
            $this->logManagerAction();
            $this->removeLock();
        }
        /* empty cache */
        $this->clearCache();

        $outputArray = $this->resource->get(array('id'));

        $outputArray['successes'] = $this->success;
        $outputArray['failures'] = $this->failures;

        $msg = "nomessage";
        if ($outputArray['successes'] > 0) {
            $msg = $this->modx->lexicon('trash.restore_success', array(
                'list' => implode(',', $this->success),
                'count_success' => count($this->success),
            ));

            if (count($this->failures)>0) {
                $msg .= '<br/>'.$this->modx->lexicon('trash.restore_err_', array(
                        // TODO get the pagetitles here
                        'list' => implode(',',$this->failures),
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
     *
     * @return boolean
     */
    public function removeLock() {
        return $this->resource->removeLock();
    }

    /**
     * Fire the UnDelete event
     *
     * @return void
     */
    public function fireAfterUnDeleteEvent() {
        $this->modx->invokeEvent('OnResourceUndelete', array(
            'id' => $this->resource->get('id'),
            'resource' => &$this->resource,
        ));
    }

    /**
     * Log the manager action
     *
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('undelete_resource', 'modResource', $this->resource->get('id'));
    }

    /**
     * Clear the site cache for the restored resources contexts
     *
     * @return void
     */
    public function clearCache() {
        $this->modx->cacheManager->refresh(array(
            'db' => array(),
            'auto_publish' => array('contexts' => $this->contexts),
            'context_settings' => array('contexts' => $this->contexts),
            'resource' => array('contexts' => $this->contexts),
        ));
    }
}

return 'modResourceTrashRestoreProcessor';