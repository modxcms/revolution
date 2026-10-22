<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Resource\Trash;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modResource;

/**
 * Gets information on deleted Resources.
 */
class GetTrashStats extends Processor
{
    use GetDeletedResourcesTrait;

    public $classKey = modResource::class;

    public function checkPermissions()
    {
        return $this->modx->hasPermission('resource_tree');
    }

    public function getLanguageTopics()
    {
        return ['resource', 'trash'];
    }

    public function process()
    {
        $responseData = ['deletedCount' => 0];

        if ($this->modx->hasPermission('purge_deleted') || $this->modx->hasPermission('undelete_document')) {
            $deletedResources = $this->getDeletedResources();
            if (!empty($deletedResources)) {
                $responseData = [
                    'deletedCount' => count($deletedResources)
                ];
            }
        }

        return $this->modx->error->success('', $responseData);
    }
}
