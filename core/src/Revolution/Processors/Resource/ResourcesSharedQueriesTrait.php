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
    protected \xPDO|\modX|null $baseQueryClass;
    /**
     * Returns a base query for fetching resources to be listed in trees and search results.
     *
     * @param array $baseSelectColumns The columns to select from the base modResource table query.
     * @return xPDOQuery The configured query object.
     */
    protected function getResourcesBaseQuery(array $baseSelectColumns = []): xPDOQuery
    {
        if (isset($this->modx) && $this->modx instanceof \modX) {
            $this->baseQueryClass = $this->modx;
        } elseif (isset($this->xpdo) && $this->xpdo instanceof \xPDO) {
            $this->baseQueryClass = $this->xpdo;
        } else {
            throw new \RuntimeException('No valid xPDO or modX instance available for building the base query.');
        }
        $c = $this->baseQueryClass->newQuery(modResource::class);
        $c = $this->getResourcesJoinCriteria($c);
        if (!empty($baseSelectColumns)) {
            $c->select($this->baseQueryClass->getSelectColumns(modResource::class, 'modResource', '', $baseSelectColumns));
        } else {
            $c->select($this->baseQueryClass->getSelectColumns(modResource::class, 'modResource'));
        }
        $c = $this->getResourcesSelectCriteria($c);
        return $c;
    }

    /**
     * Returns shared select criteria for the resources base queries.
     */
    private function getResourcesSelectCriteria(xPDOQuery $c): xPDOQuery
    {
        $resourceTable = $this->baseQueryClass->getTableName(modResource::class);
        $childrenCountSubquery = <<<SQL
            SELECT COUNT(`modResourceChildren`.`id`) 
            FROM {$resourceTable} AS modResourceChildren 
            WHERE `modResourceChildren`.`parent` = `modResource`.`id`
        SQL;
        $c->select([
            'icon' => 'modTemplate.icon',
            'contentTypeIcon' => 'modContentType.icon',
            'childrenCount' => "({$childrenCountSubquery})"
        ]);
        return $c;
    }

    /**
     * Returns shared join criteria for resources base queries, including template, content type, and children.
     */
    private function getResourcesJoinCriteria(xPDOQuery $c): xPDOQuery
    {
        if (new \ReflectionClass($this)->getShortName() === 'modResourceGroup') {
            $c->innerJoin(modResourceGroupResource::class, 'ResourceGroupResources');
        }
        $c->leftJoin(modTemplate::class, 'modTemplate', 'modResource.template = modTemplate.id');
        $c->leftJoin(modContentType::class, 'modContentType', 'modContentType.id = modResource.content_type');
        /*
            Original strategy using self join for children was set aside, in favor of a subquery, to avoid potential
            issues with MariaDB's slightly different treatment (than MySQL) of ONLY_FULL_GROUP_BY,
            particularly in strict mode:

            $c->leftJoin(modResource::class, 'modResourceChildren', 'modResource.id = modResourceChildren.parent');
            $c->select([
                'childrenCount' => 'COUNT(modResourceChildren.id)'
            ]);
            $c->groupby('modResource.id');
        */
        return $c;
    }
}
