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

use MODX\Revolution\Formatter\modManagerDateFormatter;
use MODX\Revolution\modResource;
use MODX\Revolution\modTemplate;
use MODX\Revolution\modUser;
use MODX\Revolution\Processors\Processor;
use xPDO\Cache\xPDOCacheManager;
use xPDO\xPDO;

/**
 * Returns resource data.
 *
 * @param integer $id The ID of the resource
 * @return array
 */
class Data extends Processor
{
    /** @var modResource $resource */
    public $resource;

    private modManagerDateFormatter $formatter;

    public function checkPermissions()
    {
        return $this->modx->hasPermission('view');
    }

    public function getLanguageTopics()
    {
        return ['resource', 'manager_log'];
    }

    public function initialize()
    {
        $this->formatter = $this->modx->services->get(modManagerDateFormatter::class);
        $id = $this->getProperty('id', false);
        if (empty($id)) {
            return $this->modx->lexicon('resource_err_ns');
        }
        $c = $this->modx->newQuery(modResource::class);
        $c->select([
            $this->modx->getSelectColumns(modResource::class, 'modResource'),
            'template_name' => 'Template.templatename',
            'creator' => 'CreatedBy.username',
            'editor' => 'EditedBy.username',
            'publisher' => 'PublishedBy.username',
        ]);
        $c->leftJoin(modTemplate::class, 'Template');
        $c->leftJoin(modUser::class, 'CreatedBy');
        $c->leftJoin(modUser::class, 'EditedBy');
        $c->leftJoin(modUser::class, 'PublishedBy');
        $c->where([
            'modResource.id' => $id,
        ]);
        $this->resource = $this->modx->getObject(modResource::class, $c);
        if (empty($this->resource)) {
            return $this->modx->lexicon('resource_err_nfs', ['id' => $id]);
        }
        if (!$this->resource->checkPolicy('view')) {
            return $this->modx->lexicon('permission_denied');
        }
        return true;
    }

    public function process()
    {
        $resourceArray = $this->resource->toArray('', true, true);
        $resourceArray = $this->getChanges($resourceArray);

        /* template */
        if (empty($resourceArray['template_name'])) {
            $resourceArray['template_name'] = $this->modx->lexicon('empty_template');
        }

        /* source */
        $resourceArray['buffer'] = $this->getCacheSource();
        return $this->success('', $resourceArray);
    }

    public function getCacheSource()
    {
        $this->resource->_contextKey = $this->resource->get('context_key');
        $buffer = $this->modx->cacheManager->get($this->resource->getCacheKey(), [
            xPDO::OPT_CACHE_KEY => $this->modx->getOption('cache_resource_key', null, 'resource'),
            xPDO::OPT_CACHE_HANDLER => $this->modx->getOption('cache_resource_handler', null, $this->modx->getOption(xPDO::OPT_CACHE_HANDLER)),
            xPDO::OPT_CACHE_FORMAT => (int)$this->modx->getOption('cache_resource_format', null, $this->modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
        ]);
        if ($buffer) {
            $buffer = $buffer['resource']['_content'];
        }
        return !empty($buffer) ? $buffer : $this->modx->lexicon('resource_notcached');
    }

    public function getChanges(array $resourceArray)
    {
        $unknownUser = '(' . $this->modx->lexicon('unknown') . ')';
        $resourceArray['status'] = $resourceArray['published'] ? $this->modx->lexicon('resource_published') : $this->modx->lexicon('resource_unpublished');

        $resourceArray['createdon_by'] = $this->resource->get('creator') ?: $unknownUser;
        $resourceArray['createdon_adjusted'] = $this->formatter->formatResourceDate(
            $this->resource->get('createdon'),
            'created',
            false
        );
        $resourceArray['editedon_adjusted'] = $this->formatter->formatResourceDate(
            $this->resource->get('editedon'),
            'edited',
            false
        );
        $resourceArray['editedon_by'] = $this->formatter->isEmpty($resourceArray['editedon'])
            ? '(' . $this->modx->lexicon('unedited') . ')'
            : $this->resource->get('editor')
            ;

        $resourceArray['pub_date'] = $this->formatter->formatResourceDate(
            $resourceArray['pub_date'],
            'publish',
            false
        );
        $resourceArray['unpub_date'] = $this->formatter->formatResourceDate(
            $resourceArray['unpub_date'],
            'unpublish',
            false
        );
        $resourceArray['publishedon_adjusted'] = $this->formatter->formatResourceDate(
            $this->resource->get('publishedon'),
            'published',
            false
        );
        $publisher = $this->resource->get('publisher') ?: $unknownUser;
        $resourceArray['publishedon_by'] = $this->formatter->isEmpty($resourceArray['publishedon'])
            ? '(' . $this->modx->lexicon('unpublished') . ')'
            : $publisher
            ;

        return $resourceArray;
    }
}
