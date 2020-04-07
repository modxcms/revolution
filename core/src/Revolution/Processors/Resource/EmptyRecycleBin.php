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
use MODX\Revolution\modResourceGroupResource;
use MODX\Revolution\modTemplateVarResource;

/**
 * Empties the recycle bin.
 *
 * @return boolean
 */
class EmptyRecycleBin extends Processor
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
        $resources = $this->modx->getCollection(modResource::class, ['deleted' => true]);
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

        $this->modx->logManagerAction('empty_trash', modResource::class, implode(',', $ids));

        return $this->success();
    }
}
