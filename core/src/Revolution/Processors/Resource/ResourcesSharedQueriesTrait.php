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

use MODX\Revolution\modContentType;
use MODX\Revolution\modResource;
use MODX\Revolution\modResourceGroupResource;
use MODX\Revolution\modTemplate;
use xPDO\Om\xPDOQuery;

/**
 * Provides shared query building methods for fetching resources in various contexts, such as trees and search results.
 */
trait ResourcesSharedQueriesTrait
{
    /**
     * Returns a base query for fetching resources to be listed in trees and search results.
     *
     * @param array $baseSelectColumns The columns to select from the base modResource table query.
     * @return xPDOQuery The configured query object.
     */
    protected function getResourcesBaseQuery(array $baseSelectColumns = []): xPDOQuery
    {
        $c = $this->modx->newQuery(modResource::class);
        $c = $this->getResourcesJoinCriteria($c);
        if (!empty($baseSelectColumns)) {
            $c->select($this->modx->getSelectColumns(modResource::class, 'modResource', '', $baseSelectColumns));
        } else {
            $c->select($this->modx->getSelectColumns(modResource::class, 'modResource'));
        }
        $c = $this->getResourcesSelectCriteria($c);
        $c = $this->getResourcesAggregationCriteria($c);
        return $c;
    }

    /**
     * Returns a base query for fetching resources for the resource groups tree.
     *
     * @param array $baseSelectColumns The columns to select from the base modResource table query.
     * @return xPDOQuery The configured query object.
     */
    protected function getResourceGroupsResourcesBaseQuery(array $baseSelectColumns = []): xPDOQuery
    {
        $c = $this->xpdo->newQuery(modResource::class);
        $c->innerJoin(modResourceGroupResource::class, 'ResourceGroupResources');
        $c = $this->getResourcesJoinCriteria($c);
        if (!empty($baseSelectColumns)) {
            $c->select($this->xpdo->getSelectColumns(modResource::class, 'modResource', '', $baseSelectColumns));
        } else {
            $c->select($this->xpdo->getSelectColumns(modResource::class, 'modResource'));
        }
        $c = $this->getResourcesSelectCriteria($c);
        $c = $this->getResourcesAggregationCriteria($c);
        return $c;
    }

    /**
     * Returns shared select criteria for the resources base queries.
     */
    private function getResourcesSelectCriteria(xPDOQuery $c): xPDOQuery
    {
        $c->select([
            'icon' => 'modTemplate.icon',
            'contentTypeIcon' => 'modContentType.icon',
            'childrenCount' => 'COUNT(modResourceChildren.id)'
        ]);
        return $c;
    }

    /**
     * Returns shared join criteria for resources base queries, including template, content type, and children.
     */
    private function getResourcesJoinCriteria(xPDOQuery $c): xPDOQuery
    {
        $c->leftJoin(modTemplate::class, 'modTemplate', 'modResource.template = modTemplate.id');
        $c->leftJoin(modContentType::class, 'modContentType', 'modContentType.id = modResource.content_type');
        $c->leftJoin(modResource::class, 'modResourceChildren', 'modResource.id = modResourceChildren.parent');
        return $c;
    }

    /**
     * Returns the aggregation criteria for the resources base queries, including grouping by resource ID.
     */
    private function getResourcesAggregationCriteria(xPDOQuery $c): xPDOQuery
    {
        $c->groupby('modResource.id');
        return $c;
    }
}
