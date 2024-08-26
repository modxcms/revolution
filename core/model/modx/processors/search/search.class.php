<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Class modSearchProcessor
 * Searches for elements (all but plugins) & resources
 **/
class modSearchProcessor extends modProcessor
{
    const TYPE_TEMPLATE = 'template';
    const TYPE_TV = 'tv';
    const TYPE_CHUNK = 'chunk';
    const TYPE_SNIPPET = 'snippet';
    const TYPE_PLUGIN = 'plugin';

    const TYPE_USER = 'user';
    const TYPE_RESOURCE = 'resource';

    protected $query;
    
    public $results = array();

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
                $this->searchElements('modChunk', static::TYPE_CHUNK, 'name', 'description', 'snippet');
            }
            if ($this->modx->hasPermission('edit_template')) {
                $this->searchElements('modTemplate', static::TYPE_TEMPLATE, 'templatename', 'description', 'content');
            }
            if ($this->modx->hasPermission('edit_tv')) {
                $this->searchElements('modTemplateVar', static::TYPE_TV, 'name', 'caption', 'default_text');
            }
            if ($this->modx->hasPermission('edit_snippet')) {
                $this->searchElements('modSnippet', static::TYPE_SNIPPET, 'name', 'description', 'snippet');
            }
            if ($this->modx->hasPermission('edit_plugin')) {
                $this->searchElements('modPlugin', static::TYPE_PLUGIN, 'name', 'description', 'plugincode');
            }
            if ($this->modx->hasPermission('edit_user')) {
                $this->searchUsers();
            }
        }

        return $this->outputArray($this->results);
    }

    /**
     * Perform search in resources
     *
     * @return void
     */
    protected function searchResources()
    {
        $contextKeys = [];
        $contexts = $this->modx->getIterator('modContext', ['key:!=' => 'mgr']);
        foreach ($contexts as $context) {
            $contextKeys[] = $context->get('key');
        }

        $c = $this->modx->newQuery('modResource');
        $c->leftJoin('modTemplate', 'modTemplate', 'modResource.template = modTemplate.id');
        $c->select($this->modx->getSelectColumns('modResource', 'modResource'));
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

        $c->sortby('IF(`modResource`.`pagetitle` = ' . $this->modx->quote($this->query) . ', 0, 1)');
        $c->sortby('modResource.createdon', 'DESC');

        $c->limit($this->getMaxResults());

        $collection = $this->modx->getIterator('modResource', $c);
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
        $c = $this->modx->newQuery('modUser');
        $c->select([
            $this->modx->getSelectColumns('modUser', 'modUser'),
            $this->modx->getSelectColumns('modUserProfile', 'Profile'),
        ]);
        $c->leftJoin(modUserProfile::class, 'Profile');
        $c->where([
            'username:LIKE' => '%' . $this->query . '%',
            'OR:Profile.fullname:LIKE' => '%' . $this->query .'%',
            'OR:Profile.email:LIKE' => '%' . $this->query .'%',
            'OR:id:=' => $this->query,
        ]);

        $c->sortby('IF(`username` = ' . $this->modx->quote($this->query) . ', 0, 1)');

        $c->limit($this->getMaxResults());

        /** @var modUserProfile[] $collection */
        $collection = $this->modx->getIterator('modUser', $c);

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

return 'modSearchProcessor';
