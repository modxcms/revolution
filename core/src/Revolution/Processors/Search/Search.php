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
        return (boolean)$this->modx->getOption('quick_search_in_content', null, true);
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
     * Search in resources
     */
    protected function searchResources()
    {
        $contextKeys = [];
        $contexts = $this->modx->getIterator(modContext::class, ['key:!=' => 'mgr']);
        foreach ($contexts as $context) {
            $contextKeys[] = $context->get('key');
        }

        $c = $this->modx->newQuery(modResource::class);
        $c->leftJoin(modTemplate::class, 'modTemplate', 'modResource.template = modTemplate.id');
        $c->select($this->modx->getSelectColumns(modResource::class, 'modResource'));
        $c->select('modTemplate.icon as icon');

        $querySearch = [
            'modResource.pagetitle:LIKE' => '%' . $this->query .'%',
            'OR:modResource.longtitle:LIKE' => '%' . $this->query .'%',
            'OR:modResource.alias:LIKE' => '%' . $this->query .'%',
            'OR:modResource.description:LIKE' => '%' . $this->query .'%',
            'OR:modResource.introtext:LIKE' => '%' . $this->query .'%',
        ];
        if ($this->searchInContent()) {
            $querySearch['OR:modResource.content:LIKE'] = '%' . $this->query .'%';
        }
        $querySearch['OR:modResource.id:='] = $this->query;
        $queryContext = [
            'modResource.context_key:IN' => $contextKeys,
        ];
        $c->where($querySearch, $queryContext);

        $c->sortby('modResource.createdon', 'DESC');

        $c->limit($this->getMaxResults());

        $collection = $this->modx->getIterator(modResource::class, $c);
        /** @var modResource $record */
        foreach ($collection as $record) {
            $this->results[] = [
                'name' => $this->modx->hasPermission('tree_show_resource_ids')
                    ? $record->get('pagetitle') . ' (' . $record->get('id') . ')'
                    : $record->get('pagetitle'),
                '_action' => 'resource/update&id=' . $record->get('id'),
                'description' => $record->get('description'),
                'type' => static::TYPE_RESOURCE . 's',
                'class' => $record->get('class_key'),
                'icon' => str_replace('icon-', '', $record->get('icon'))
            ];
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
            'OR:' . $descriptionField . ':LIKE' => '%' . $this->query .'%',
        ];
        if ($this->searchInContent() && !empty($contentField)) {
            $querySearch['OR:' . $contentField . ':LIKE'] = '%' . $this->query .'%';
        }
        $querySearch['OR:id:='] = $this->query;
        $c->where($querySearch);

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
            'OR:Profile.fullname:LIKE' => '%' . $this->query .'%',
            'OR:Profile.email:LIKE' => '%' . $this->query .'%',
            'OR:id:=' => $this->query,
        ]);

        $c->limit($this->getMaxResults());

        /** @var modUserProfile[] $collection */
        $collection = $this->modx->getIterator(modUser::class, $c);

        foreach ($collection as $record) {
            $this->results[] = [
                'name' => $record->get('username'),
                'description' => $record->get('fullname') .' / '. $record->get('email'),
                '_action' => 'security/user/update&id=' . $record->get('internalKey'),
                'type' => static::TYPE_USER . 's',
            ];
        }
    }
}
