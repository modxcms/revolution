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
use MODX\Revolution\modUser;
use MODX\Revolution\modUserProfile;

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
     * Returns resource IDs that have the search query in any TV value.
     *
     * @return array<int, int>
     */
    protected function getResourceIdsMatchingTvValues(): array
    {
        if (!$this->searchInContent()) {
            return [];
        }

        $escaped = addcslashes($this->query, '%_');
        $c = $this->modx->newQuery(modTemplateVarResource::class);
        $c->select('contentid');
        $c->where(['value:LIKE' => '%' . $escaped . '%']);
        $c->groupby('contentid');
        $c->limit($this->getMaxResults() * 2);

        $ids = [];
        foreach ($this->modx->getIterator(modTemplateVarResource::class, $c) as $row) {
            $ids[] = (int) $row->get('contentid');
        }

        return array_values(array_unique($ids));
    }

    /**
     * Builds search criteria and context for resource query, including TV-matched IDs.
     *
     * @param array<int, string> $contextKeys
     * @return array{search: array, context: array, tvIds: array<int, int>}
     */
    protected function buildResourceSearchCriteria(array $contextKeys): array
    {
        $querySearch = [
            'modResource.pagetitle:LIKE' => '%' . $this->query . '%',
            'OR:modResource.longtitle:LIKE' => '%' . $this->query . '%',
            'OR:modResource.alias:LIKE' => '%' . $this->query . '%',
            'OR:modResource.description:LIKE' => '%' . $this->query . '%',
            'OR:modResource.introtext:LIKE' => '%' . $this->query . '%',
        ];
        $tvIds = [];
        if ($this->searchInContent()) {
            $querySearch['OR:modResource.content:LIKE'] = '%' . $this->query . '%';
            $tvIds = $this->getResourceIdsMatchingTvValues();
            if (!empty($tvIds)) {
                $querySearch['OR:modResource.id:IN'] = $tvIds;
            }
        }
        $querySearch['OR:modResource.id:='] = $this->query;
        $queryContext = [
            'modResource.context_key:IN' => $contextKeys,
        ];

        return ['search' => $querySearch, 'context' => $queryContext, 'tvIds' => $tvIds];
    }

    /**
     * Applies relevance-based sort order to the resource search query.
     * Sort levels must stay in sync with fields in buildResourceSearchCriteria().
     *
     * @param \xPDO\Om\xPDOQuery $c
     * @param array<int, int> $tvIds
     */
    protected function applyResourceSearchSortBy(\xPDO\Om\xPDOQuery $c, array $tvIds): void
    {
        $q = $this->modx->quote($this->query);
        $qLike = $this->modx->quote($this->query . '%');
        $qContains = $this->modx->quote('%' . $this->query . '%');

        $c->sortby('(`modResource`.`pagetitle` = ' . $q . ')', 'DESC');
        $c->sortby('(`modResource`.`pagetitle` LIKE ' . $qLike . ')', 'DESC');
        $otherFieldsLike = '(`modResource`.`longtitle` LIKE ' . $qContains
            . ' OR `modResource`.`alias` LIKE ' . $qContains
            . ' OR `modResource`.`description` LIKE ' . $qContains
            . ' OR `modResource`.`introtext` LIKE ' . $qContains . ')';
        $c->sortby($otherFieldsLike, 'DESC');
        if ($this->searchInContent()) {
            $c->sortby('(`modResource`.`content` LIKE ' . $qContains . ')', 'DESC');
            if (!empty($tvIds)) {
                $ids = implode(',', array_map('intval', $tvIds));
                $c->sortby('(`modResource`.`id` IN (' . $ids . '))', 'DESC');
            }
        }
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
        $c->select($this->modx->getSelectColumns(modResource::class, 'modResource'));
        $c->select('modTemplate.icon as icon');
        $c->where($criteria['search'], $criteria['context']);
        $this->applyResourceSearchSortBy($c, $criteria['tvIds']);
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
