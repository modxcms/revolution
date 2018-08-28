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
 * Empties the recycle bin.
 *
 * @return boolean
 * @package    modx
 * @subpackage processors.resource
 */
class modResourceTrashPurgeProcessor extends modProcessor {

    /** @var modResource[] $resources */
    public $resources;

    /** @var array $ids The ids of the resources to be deleted. */
    public $ids;

    /** @var array $failures Failed ids of purged resources */
    private $failures;

    public function checkPermissions() {
        return $this->modx->hasPermission('purge_deleted');
    }

    public function getLanguageTopics() {
        return array('resource', 'trash');
    }

    /**
     * @return bool|null|string
     */
    public function initialize() {
        $idlist = $this->getProperty('ids', false);

        if (!$idlist) {
            return $this->modx->lexicon('resource_err_ns');
        }

        $this->ids = explode(',', $idlist);
        $this->resources = $this->modx->getIterator('modResource', array(
            'deleted' => true,
            'id:IN' => $this->ids,
        ));

        /* validate resource can be deleted: this is necessary in advance, because
           otherwise the tvs might already have been removed, when the policy on the
           resource is checked. (just a guess, does not harm to check here and again on
           processing.
         */

        $this->failures = array();
        $success = array();
        $policies_needed = array(
            'save' => true,
            'delete' => true,
            'load' => true,
            'list' => true,
            'edit' => true,
        );
        foreach ($this->resources as $resource) {
            $context_allowed = $this->modx->getContext($resource->get('context_key'));
            $policy_allowed = $resource->checkPolicy($policies_needed);

            // again, if we do not want to allow deleting of resources in contexts we are not allowed to see, we have to check that manually
            // this _should_ be done by the resources checkPolicy
            if (!$context_allowed) {
                $this->modx->log(modX::LOG_LEVEL_WARN,
                    '[purge] context access denied for resource ' . $resource->id . ' in context ' . $resource->get('context_key'));
                $this->failures[] = $resource->id;
            }
            if (!$policy_allowed) {
                $this->modx->log(modX::LOG_LEVEL_WARN,
                    '[purge] permissions denied for resource ' . $resource->id . ': save=' . !$resource->checkPolicy(array('save')) . ', delete=' . $resource->checkPolicy(array('delete')));
                $this->failures[] = $resource->id;
            }
            if ($policy_allowed && $context_allowed) {
                $success[] = $resource->id;
            }
        }

        // we refresh the resources list here for the processor
        $this->ids = $success;
        if (empty($success)) {
            $this->resources = array();
        } else {
            $this->resources = $this->modx->getCollection('modResource', array(
                'deleted' => true,
                'id:IN' => $success,
            ));
        }

        return true;
    }

    public function process() {
        // fire before empty trash event
        $this->modx->invokeEvent('OnBeforeEmptyTrash', array(
            'ids' => &$this->ids,
            'resources' => &$this->resources,
        ));

        // we track success and failure independently, as we don't want
        // to stop in case of single files failing
        $success = array();

        $this->failures = array(); // we are no more interested in the previous failures, as they are already filtered out

        $permissionsForPurge = array(
            'save' => true,
            'delete' => true,
        );

        /** @var modResource $resource */
        foreach ($this->resources as $resource) {
            if (!$resource->checkPolicy($permissionsForPurge)) {
                continue;
            }

            $id = $resource->get('id');

            $resourceGroupResources = $resource->getMany('ResourceGroupResources');
            $templateVarResources = $resource->getMany('TemplateVarResources');

            /** @var modResourceGroupResource $resourceGroupResource */
            foreach ($resourceGroupResources as $resourceGroupResource) {
                $resourceGroupResource->remove();
            }

            /** @var modTemplateVarResource $templateVarResource */
            foreach ($templateVarResources as $templateVarResource) {
                $templateVarResource->remove();
            }

            // TODO isn't that a problem here?
            // If resource remove now fails we already removed the tvs!
            // shouldn't resource->remove also take care of the tvs and resource groups?
            if (!$resource->remove()) {
                // we just add the id to the failures here (we may already have failures from the init with permissions)
                $this->failures[] = $id;
            } else {
                $success[] = $id;
            }
        }

        $this->modx->invokeEvent('OnEmptyTrash', array(
            'num_deleted' => count($success),
            'resources' => &$this->resources,
            'ids' => &$success,
        ));

        $this->modx->logManagerAction('empty_trash', 'modResource', implode(',', $success));

        // if nothing was successfully purged, we throw a failure here
        if (count($this->failures) > 0 && count($success) == 0) {
            return $this->failure($this->modx->lexicon('trash.purge_err_delete', array(
                // TODO get the pagetitles here
                'list' => implode(',', $this->failures),
                'count' => count($this->failures),
            )));
        }

        if (count($this->failures) == 0 && count($success) == 0) {
            return $this->success($this->modx->lexicon('trash.purge_err_nothing'));
        }

        $msg = '';
        if (count($success) > 0) {
            $this->modx->cacheManager->refresh();
            $msg = $this->modx->lexicon('trash.purge_success_delete', array(
                'list' => implode(',', $success),
                'count' => count($success),
            ));
            if (count($this->failures) > 0) {
                $msg .= '<br/>' . $this->modx->lexicon('trash.purge_err_delete', array(
                        // TODO get the pagetitles here
                        'list' => implode(',', $this->failures),
                        'count' => count($this->failures),
                    ));
            }
        }

        return $this->success($msg, array(
            'count_success' => count($success),
            'count_failures' => count($this->failures)
        ));
    }
}

return 'modResourceTrashPurgeProcessor';
