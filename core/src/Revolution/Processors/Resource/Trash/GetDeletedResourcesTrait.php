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

use MODX\Revolution\modResource;
use MODX\Revolution\modContext;

trait GetDeletedResourcesTrait
{
    /**
     * Gets a collection of deleted Resources accessible to the current user,
     * honoring Context and Resource group permissions.
     *
     * @return array An array of Resource instances
     */
    protected function getDeletedResources(): array
    {
        $listableContexts = $this->getListableContexts();
        if (empty($listableContexts)) {
            return [];
        }
        $resourceClass = $this->classKey ?? modResource::class;
        $c = $this->modx->newQuery($resourceClass);
        $c->select($this->modx->getSelectColumns($resourceClass, $c->getAlias(), '', ['id', 'context_key']));
        $c->where([
            $c->getAlias() . '.deleted' => true,
            $c->getAlias() . '.context_key:IN' => $listableContexts
        ]);

        /*
            Note that getCollection() handles filtering out items in resource groups
            not accessible to the user by way of enforcing load permissions via
            modAccessibleObject::loadInstance.
        */
        $resources = $this->modx->getCollection($resourceClass, $c);

        return $resources;
    }

    /**
     * Gets a list of Context keys that the current user has permission to list.
     *
     * @return array An array of listable Context keys
     */
    protected function getListableContexts(): array
    {
        $contexts = $this->modx->getCollection(modContext::class, ['key:!=' => 'mgr']);
        if (!$contexts) {
            return [];
        }
        $listableContexts = [];
        foreach ($contexts as $context) {
            if ($context->checkPolicy('list')) {
                $listableContexts[] = $context->get('key');
            }
        }
        return $listableContexts;
    }

    /**
     * Gets a collection of deleted Resources accessible to the current user,
     * honoring Context and Resource group permissions.
     *
     * @return array An array of Resource instances
     */
    protected function getListableDeletedResources(): array
    {
        $resources = $this->modx->getCollection(modResource::class, [
            'deleted' => true,
            'context_key:IN' => $this->getListableContexts()
        ]);
        return $resources ? $resources : [];
    }

    /**
     * Filters the provided IDs to only include those of deleted Resources accessible to the current user.
     *
     * @param array|string|null $ids The user-specified Resource IDs from the request or input
     * @return array The filtered list of both the Resource IDs and Resource instances
     */
    protected function getFilteredDeletedResourcesData(array|string|null $ids): array
    {
        $data = [
            'resourceIds' => [],
            'resources' => []
        ];

        if (empty($ids)) {
            return $data;
        }

        /*
            As a basic initial filter, collect the deleted Resources, if any, the current user
            has permissions to view as candidates for undelete or purge.
        */
        $listableResources = $this->getListableDeletedResources();
        if (empty($listableResources)) {
            return $data;
        }

        $ids = is_string($ids) ? array_map('intval', explode(',', $ids)) : array_map('intval', $ids);

        $resources = array_filter($listableResources, static function ($resource) use ($ids) {
            return in_array((int)$resource->get('id'), $ids);
        });

        if (count($resources) > 0) {
            $data['resources'] = $resources;

            // Empty array as the third argument triggers reindex of array keys starting from 0
            $resourceIds = array_map(static fn($resource) => (int)$resource->get('id'), $resources, []);

            $data['resourceIds'] = $resourceIds;
        }

        return $data;
    }

    /**
     * Gets the count of deleted Resources accessible to the current user.
     */
    protected function getDeletedResourcesCount(): int
    {
        $deletedResources = $this->getDeletedResources();
        return !empty($deletedResources) ? count($deletedResources) : 0;
    }

    /**
     * Sets the deleted resource count on the processor's object.
     */
    protected function setDeletedResourceCount(): void
    {
        $this->object->set('deletedCount', $this->getDeletedResourcesCount());
    }

    /**
     * Updates the deleted resource count in the output/response data array.
     *
     * @param array $dataArray The output array to update
     */
    protected function updateDeletedResourceCount(array &$dataArray): void
    {
        $dataArray['deletedCount'] = $this->getDeletedResourcesCount();
    }
}
