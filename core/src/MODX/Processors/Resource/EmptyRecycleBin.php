<?php

namespace MODX\Processors\Resource;

use MODX\modResource;
use MODX\modResourceGroupResource;
use MODX\modTemplateVarResource;
use MODX\Processors\modProcessor;

/**
 * Empties the recycle bin.
 *
 * @return boolean
 *
 * @package modx
 * @subpackage processors.resource
 */
class EmptyRecycleBin extends modProcessor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('purge_deleted');
    }


    public function getLanguageTopics()
    {
        return ['resource'];
    }


    public function process()
    {
        /* get resources */
        $resources = $this->modx->getCollection('modResource', ['deleted' => true]);
        $count = count($resources);

        $ids = [];
        /** @var modResource $resource */
        foreach ($resources as $resource) {
            $ids[] = $resource->get('id');
        }

        $this->modx->invokeEvent('OnBeforeEmptyTrash', [
            'ids' => &$ids,
            'resources' => &$resources,
        ]);

        reset($resources);
        $ids = [];
        /** @var modResource $resource */
        foreach ($resources as $resource) {
            if (!$resource->checkPolicy('delete')) continue;

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

            if ($resource->remove() == false) {
                return $this->failure($this->modx->lexicon('resource_err_delete'));
            } else {
                $ids[] = $resource->get('id');
            }
        }

        $this->modx->invokeEvent('OnEmptyTrash', [
            'num_deleted' => $count,
            'resources' => &$resources,
            'ids' => &$ids,
        ]);

        $this->modx->logManagerAction('empty_trash', 'modResource', implode(',', $ids));

        return $this->success();
    }
}