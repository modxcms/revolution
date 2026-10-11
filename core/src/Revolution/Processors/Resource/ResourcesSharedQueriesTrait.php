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
use MODX\Revolution\modTemplate;
use xPDO\Om\xPDOQuery;

trait ResourcesSharedQueriesTrait
{
    /**
     * Returns a base query for fetching resources to be listed in trees and search results.
     *
     * @param array $baseSelectColumns The columns to select from the base modResource table query.
     * @return xPDOQuery The configured query object.
     */
    protected function getResourcesBaseQuery(array $baseSelectColumns): xPDOQuery
    {
        $c = $this->modx->newQuery(modResource::class);
        $c->leftJoin(modTemplate::class, 'modTemplate', 'modResource.template = modTemplate.id');
        $c->leftJoin(modContentType::class, 'modContentType', 'modContentType.id = modResource.content_type');
        $c->leftJoin(modResource::class, 'modResourceChildren', 'modResource.id = modResourceChildren.parent');
        $c->select($this->modx->getSelectColumns(modResource::class, 'modResource', '', $baseSelectColumns));
        $c->select([
            'icon' => 'modTemplate.icon',
            'contentTypeIcon' => 'modContentType.icon',
            'childrenCount' => 'COUNT(modResourceChildren.id)'
        ]);
        $c->groupby('modResource.id');

        return $c;
    }
}
