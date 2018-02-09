<?php

/**
 * Restores deleted files.
 *
 * @return boolean
 * @package    modx
 * @subpackage processors.resource
 */
class modResourceTrashRestoreProcessor extends modProcessor
{

    /** @var modResource $resource */
    public $resource;

    private $resourceList;
    private $idList;
    private $contexts;

    public function getLanguageTopics()
    {
        return array('resource', 'trash');
    }

    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        // we expect a list of files here
        $this->idList = explode(',', $this->getProperty('ids', false));
        $this->modx->log(1, "Restorelist: " . print_r($this->idList, true));
        $this->contexts = array();

        if (count($this->idList)===0) {
            return $this->modx->lexicon('resource_err_ns');
        }

        // and now retrieve a collection of resources here
        $this->resourceList = $this->modx->getCollection('modResource', array(
            'deleted' => true,
            'id:IN'   => $this->idList,
        ));
        $count = sizeof($this->resourceList);

        if (empty($this->resourceList)) {
            return $this->modx->lexicon('resource_err_nfs', array('ids' => $this->idList));
        }

        $this->modx->log(1, "found " . $count);

        foreach ($this->resourceList as $resource) {
            $this->modx->log(1, "restoring " . $resource->get('id') . ": " . $resource->get('pagetitle'));
        }

        /* validate resource can be deleted */
        //if (!$this->resource->checkPolicy(array('save' => true, 'delete' => true))) {
        //    return $this->modx->lexicon('permission_denied');
        //}
        return true;
    }

    public function process()
    {
        foreach ($this->resourceList as $resource) {
            $this->resource = $resource;
            $this->contexts[] = ($this->resource->get('context_key'));

            if (!$this->addLock()) {
                return $this->failure($this->modx->lexicon('resource_locked_by',
                    array('id' => $this->resource->get('id'), 'user' => $this->lockedUser->get('username'))));
            }

            /* 'undelete' the resource. */
            $this->resource->set('deleted', false);
            $this->resource->set('deletedby', 0);
            $this->resource->set('deletedon', 0);

            if ($this->resource->save()==false) {
                $this->resource->removeLock();

                return $this->failure($this->modx->lexicon('resource_err_undelete'));
            }

            // TODO this still has to be discussed: what happens to the children?
            //$this->unDeleteChildren($this->resource->get('id'), $this->resource->get('deletedon'));

            $this->fireAfterUnDeleteEvent();

            /* log manager action */
            $this->logManagerAction();
            $this->removeLock();
        }
        /* empty cache */
        $this->clearCache();

        $deletedCount = $this->modx->getCount('modResource', array('deleted' => 1));

        $outputArray = $this->resource->get(array('id'));

        $outputArray['deletedCount'] = $deletedCount;

        return $this->modx->error->success('', $outputArray);
    }

    /**
     * Add a lock to the Resource while undeleting it
     *
     * @return boolean
     */
    public function addLock()
    {
        $locked = $this->resource->addLock();
        if ($locked!==true) {
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
    public function removeLock()
    {
        return $this->resource->removeLock();
    }

    /**
     * Fire the UnDelete event
     *
     * @return void
     */
    public function fireAfterUnDeleteEvent()
    {
        $this->modx->invokeEvent('OnResourceUndelete', array(
            'id'       => $this->resource->get('id'),
            'resource' => &$this->resource,
        ));
    }

    /**
     * Log the manager action
     *
     * @return void
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction('undelete_resource', 'modResource', $this->resource->get('id'));
    }

    /**
     * Clear the site cache for the restored resources contexts
     *
     * @return void
     */
    public function clearCache()
    {
        $this->modx->log(modX::LOG_LEVEL_DEBUG, 'Refreshing contexts of restored resources: '.print_r($this->contexts,true));
        $this->modx->cacheManager->refresh(array(
            'db'               => array(),
            'auto_publish'     => array('contexts' => $this->contexts),
            'context_settings' => array('contexts' => $this->contexts),
            'resource'         => array('contexts' => $this->contexts),
        ));
    }
}

return 'modResourceTrashRestoreProcessor';