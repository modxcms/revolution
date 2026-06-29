<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Search;

use MODX\Revolution\modChunk;
use MODX\Revolution\modContext;
use MODX\Revolution\modElement;
use MODX\Revolution\modPlugin;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modResource;
use MODX\Revolution\modSnippet;
use MODX\Revolution\modTemplate;
use MODX\Revolution\modTemplateVar;
use MODX\Revolution\modTemplateVarResource;
use MODX\Revolution\modTemplateVarTemplate;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserProfile;
use xPDO\Om\xPDOQuery;

/**
 * Searches for elements, resources and users
 **/
class Search extends Processor
{
    const TYPE_TEMPLATE = 'template';
    const TYPE_TV = 'tv';
    const TYPE_CHUNK = 'chunk';
    const TYPE_SNIPPET = 'snippet';
    const TYPE_PLUGIN = 'plugin';

    const TYPE_USER = 'user';
    const TYPE_RESOURCE = 'resource';

    protected $query;

    public $results = [];

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('search');
    }

    /**
     * Returns max records per search request
     * @return int
     */
    protected function getMaxResults()
    {
        return (int)$this->modx->getOption('quick_search_result_max', null, 10);
    }

    /**
     * @return bool
     */
    protected function searchInContent()
    {
        return (bool)$this->modx->getOption('quick_search_in_content', null, true);
    }

    /**
     * @return string JSON formatted results
     */
    public function process()
    {
        $this->query = trim($this->getProperty('query'));
        if (!empty($this->query)) {
            if ($this->modx->hasPermission('edit_document')) {
                $this->searchResources();
            }
            if ($this->modx->hasPermission('edit_chunk')) {
                $this->searchElements(modChunk::class, static::TYPE_CHUNK, 'name', 'description', 'snippet');
            }
            if ($this->modx->hasPermission('edit_template')) {
                $this->searchElements(modTemplate::class, static::TYPE_TEMPLATE, 'templatename', 'description', 'content');
            }
            if ($this->modx->hasPermission('edit_tv')) {
                $this->searchElements(modTemplateVar::class, static::TYPE_TV, 'name', 'caption', 'default_text');
            }
            if ($this->modx->hasPermission('edit_snippet')) {
                $this->searchElements(modSnippet::class, static::TYPE_SNIPPET, 'name', 'description', 'snippet');
            }
            if ($this->modx->hasPermission('edit_plugin')) {
                $this->searchElements(modPlugin::class, static::TYPE_PLUGIN, 'name', 'description', 'plugincode');
            }
            if ($this->modx->hasPermission('edit_user')) {
                $this->searchUsers();
            }
        }

        return $this->outputArray($this->results);
    }

    /**
     * Returns context keys for resource search (excluding mgr).
     *
     * @return array<int, string>
     */
    protected function getResourceContextKeys(): array
    {
        $contextKeys = [];
        $contexts = $this->modx->getIterator(modContext::class, ['key:!=' => 'mgr']);
        foreach ($contexts as $context) {
            $contextKeys[] = $context->get('key');
        }
        return $contextKeys;
    }

    /**
     * Returns the search query as a LIKE pattern with wildcard characters escaped.
     */
    protected function getEscapedQueryLike(): string
    {
        return '%' . addcslashes($this->query, '%_') . '%';
    }

    /**
     * SQL expression for the effective TV value on a resource (stored value or TV default).
     */
    protected function getEffectiveTvValueSql(): string
    {
        return 'IF(ISNULL(`TvResource`.`value`) OR `TvResource`.`value` = \'\', `Tv`.`default_text`, `TvResource`.`value`)';
    }

    /**
     * Adds LEFT JOINs for template TVs and per-resource TV values.
     *
     * @param \xPDO\Om\xPDOQuery $c
     */
    protected function applyResourceTvJoin(\xPDO\Om\xPDOQuery $c): void
    {
        $c->leftJoin(modTemplateVarTemplate::class, 'TvTemplate', [
            'TvTemplate.templateid = modResource.template',
        ]);
        $c->leftJoin(modTemplateVar::class, 'Tv', 'Tv.id = TvTemplate.tmplvarid');
        $c->leftJoin(modTemplateVarResource::class, 'TvResource', [
            'TvResource.contentid = modResource.id',
            'AND:TvResource.tmplvarid = Tv.id',
        ]);
    }

    /**
     * SQL condition matching resources whose effective TV value contains the query.
     */
    protected function getTvValueMatchCondition(): string
    {
        return '(' . $this->getEffectiveTvValueSql() . ' LIKE ' . $this->modx->quote($this->getEscapedQueryLike())
            . ' AND `Tv`.`id` IS NOT NULL)';
    }

    /**
     * Builds search criteria and context for resource query.
     *
     * TV values are always included here; resource content is gated by quick_search_in_content.
     *
     * @param array<int, string> $contextKeys
     * @return array{search: array, context: array}
     */
    protected function buildResourceSearchCriteria(array $contextKeys): array
    {
        $like = $this->getEscapedQueryLike();
        $querySearch = [
            'modResource.pagetitle:LIKE' => $like,
            'OR:modResource.longtitle:LIKE' => $like,
            'OR:modResource.alias:LIKE' => $like,
            'OR:modResource.description:LIKE' => $like,
            'OR:modResource.introtext:LIKE' => $like,
        ];
        if ($this->searchInContent()) {
            $querySearch['OR:modResource.content:LIKE'] = $like;
        }
        $querySearch['OR:modResource.id:='] = $this->query;
        $queryContext = [
            'modResource.context_key:IN' => $contextKeys,
        ];

        return ['search' => $querySearch, 'context' => $queryContext];
    }

    /**
     * Applies relevance-based sort order to the resource search query.
     * Sort levels must stay in sync with fields in buildResourceSearchCriteria().
     *
     * @param \xPDO\Om\xPDOQuery $c
     */
    protected function applyResourceSearchSortBy(\xPDO\Om\xPDOQuery $c): void
    {
        $q = $this->modx->quote($this->query);
        $qLike = $this->modx->quote($this->query . '%');
        $qContains = $this->modx->quote($this->getEscapedQueryLike());

        $c->sortby('(`modResource`.`pagetitle` = ' . $q . ')', 'DESC');
        $c->sortby('(`modResource`.`pagetitle` LIKE ' . $qLike . ')', 'DESC');
        $c->sortby('(`modResource`.`pagetitle` LIKE ' . $qContains . ')', 'DESC');
        $otherFieldsLike = '(`modResource`.`longtitle` LIKE ' . $qContains
            . ' OR `modResource`.`alias` LIKE ' . $qContains
            . ' OR `modResource`.`description` LIKE ' . $qContains
            . ' OR `modResource`.`introtext` LIKE ' . $qContains . ')';
        $c->sortby($otherFieldsLike, 'DESC');
        if ($this->searchInContent()) {
            $c->sortby('(`modResource`.`content` LIKE ' . $qContains . ')', 'DESC');
        }
        $c->sortby('(' . $this->getTvValueMatchCondition() . ')', 'DESC');
        $c->sortby('modResource.createdon', 'DESC');
    }

    /**
     * Formats a resource record for the search results array.
     *
     * @param modResource $record
     * @return array<string, mixed>
     */
    protected function formatResourceSearchResult(modResource $record): array
    {
        return [
            'name' => $this->modx->hasPermission('tree_show_resource_ids')
                ? $record->get('pagetitle') . ' (' . $record->get('id') . ')'
                : $record->get('pagetitle'),
            '_action' => 'resource/update&id=' . $record->get('id'),
            'description' => $record->get('description'),
            'type' => static::TYPE_RESOURCE . 's',
            'class' => $record->get('class_key'),
            'icon' => str_replace('icon-', '', $record->get('icon')),
        ];
    }

    /**
     * Search in resources
     */
    protected function searchResources()
    {
        $contextKeys = $this->getResourceContextKeys();
        $criteria = $this->buildResourceSearchCriteria($contextKeys);

        $c = $this->modx->newQuery(modResource::class);
        $c->leftJoin(modTemplate::class, 'modTemplate', 'modResource.template = modTemplate.id');
        $this->applyResourceTvJoin($c);
        $c->distinct();
        $c->select($this->modx->getSelectColumns(modResource::class, 'modResource'));
        $c->select('modTemplate.icon as icon');
        $c->where($criteria['search'], $criteria['context']);
        $c->where($this->getTvValueMatchCondition(), xPDOQuery::SQL_OR);
        $this->applyResourceSearchSortBy($c);
        $c->limit($this->getMaxResults());

        foreach ($this->modx->getIterator(modResource::class, $c) as $record) {
            $this->results[] = $this->formatResourceSearchResult($record);
        }
    }

    /**
     * Searches elements - chunks, snippets, tvs, templates, plugins
     * @param $class
     * @param string $type
     * @param string $nameField
     * @param string $descriptionField
     * @param string $contentField
     */
    protected function searchElements($class, $type = '', $nameField = 'name', $descriptionField = 'description', $contentField = '')
    {
        $c = $this->modx->newQuery($class);
        $querySearch = [
            $nameField . ':LIKE' => '%' . $this->query . '%',
            'OR:' . $descriptionField . ':LIKE' => '%' . $this->query . '%',
        ];
        if ($this->searchInContent() && !empty($contentField)) {
            $querySearch['OR:' . $contentField . ':LIKE'] = '%' . $this->query . '%';
        }
        $querySearch['OR:id:='] = $this->query;
        $c->where($querySearch);

        $c->sortby('IF(`' . $nameField . '` = ' . $this->modx->quote($this->query) . ', 0, 1)');

        $c->limit($this->getMaxResults());

        $collection = $this->modx->getIterator($class, $c);

        /** @var modElement $record */
        foreach ($collection as $record) {
            $this->results[] = [
                'name' => $record->get($nameField),
                'description' => $record->get($descriptionField),
                '_action' => 'element/' . $type . '/update&id=' . $record->get('id'),
                'type' => $type . 's'
            ];
        }
    }

    /**
     * Searches users registered in the system
     */
    protected function searchUsers()
    {
        $c = $this->modx->newQuery(modUser::class);
        $c->select([
            $this->modx->getSelectColumns(modUser::class, 'modUser'),
            $this->modx->getSelectColumns(modUserProfile::class, 'Profile'),
        ]);
        $c->leftJoin(modUserProfile::class, 'Profile');
        $c->where([
            'username:LIKE' => '%' . $this->query . '%',
            'OR:Profile.fullname:LIKE' => '%' . $this->query . '%',
            'OR:Profile.email:LIKE' => '%' . $this->query . '%',
            'OR:id:=' => $this->query,
        ]);

        $c->sortby('IF(`username` = ' . $this->modx->quote($this->query) . ', 0, 1)');

        $c->limit($this->getMaxResults());

        /** @var modUserProfile[] $collection */
        $collection = $this->modx->getIterator(modUser::class, $c);

        foreach ($collection as $record) {
            $this->results[] = [
                'name' => $record->get('username'),
                'description' => $record->get('fullname') . ' / ' . $record->get('email'),
                '_action' => 'security/user/update&id=' . $record->get('internalKey'),
                'type' => static::TYPE_USER . 's',
            ];
        }
    }
}
