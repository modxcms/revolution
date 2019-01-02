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
    public $maxResults = 5;
    public $actionToken = ':';
    private $actions = array();

    protected $query;
    public $results = array();

    public function checkPermissions() {
        return $this->modx->hasPermission('search');
    }

    /**
     * @return string JSON formatted results
     */
    public function process()
    {
        $this->query = $this->getProperty('query');
        if (!empty($this->query)) {
            if (strpos($this->query, ':') === 0) {
                // upcoming "launch actions"
                //$this->searchActions();
            } else {
                // Search elements & resources
                if ($this->modx->hasPermission('edit_document')) {
                    $this->searchResources();
                }
                if ($this->modx->hasPermission('edit_chunk')) {
                    $this->searchChunks();
                }
                if ($this->modx->hasPermission('edit_template')) {
                    $this->searchTemplates();
                }
                if ($this->modx->hasPermission('edit_tv')) {
                    $this->searchTVs();
                }
                if ($this->modx->hasPermission('edit_snippet')) {
                    $this->searchSnippets();
                }
                if ($this->modx->hasPermission('edit_plugin')) {
                    $this->searchPlugins();
                }
                if ($this->modx->hasPermission('edit_user')) {
                    $this->searchUsers();
                }
            }
        }

        return $this->outputArray($this->results);
    }

    /**
     * Dummy method to micmic actions search
     */
    public function searchActions()
    {
        $type = 'actions';

        $query = ltrim($this->query, $this->actionToken);
        $this->actions = array(
            array(
                'name' => 'Welcome',
                '_action' => 'welcome',
                'description' => 'Go back home',
                'type' => $type,
                'perms' => array(),
            ),
            array(
                'name' => 'Error log',
                '_action' => 'system/event',
                'description' => 'View error log',
                'type' => $type,
                'perms' => array(),
            ),
            array(
                'name' => 'Clear cache',
                '_action' => 'system/refresh_site',
                'description' => 'Refresh the cache',
                'type' => $type,
                'perms' => array(),
            ),
            array(
                'name' => 'Edit chunk',
                '_action' => 'element/chunk/update',
                'description' => 'Edit the given chunk',
                'type' => $type,
                'perms' => array(),
            ),
        );

        return $this->filterActions($query);
//        $class = 'modMenu';
//        $c = $this->modx->newQuery($class);
//        $c->where(array(
//            'action:LIKE' => '%' . $this->query . '%',
//        ));
//        $c->limit($this->maxResults);
//
//        $collection = $this->modx->getCollection($class, $c);
//        /** @var modMenu $record */
//        foreach ($collection as $record) {
//            $output[] = array(
//                'name' => $record->get('text'),
//                'action' => $record->get('action'),
//                'description' => $record->get('description'),
//                'type' => 'Actions',
//            );
//        }
    }

    private function filterActions($query)
    {
        // source : http://stackoverflow.com/questions/5808923/filter-values-from-an-array-similar-to-sql-like-search-using-php
        $query = preg_quote($query, '~');
        $names = array();
//        $actions = array();
//        $descriptions = array();
        foreach ($this->actions as $idx => $action) {
            $names[$idx] = $action['name'];
//            $actions[$idx] = $action['action'];
//            $descriptions[$idx] = $action['description'];
        }
        $results = preg_grep('~' . $query . '~', $names);
//        $results = array_merge($results, preg_grep('~' . $this->query . '~', $actions));
//        $results = array_merge($results, preg_grep('~' . $this->query . '~', $descriptions));

        //$output = array();
        if ($results) {
            foreach ($results as $idx => $field) {
                $this->results[] = $this->actions[$idx];
            }
        }

        //$output = array_unique($output);

        //return $output;
    }

    /**
     * Perform search in resources
     *
     * @return void
     */
    public function searchResources()
    {
        $type = 'resources';
        $typeLabel = $this->modx->lexicon('search_resulttype_' . $type);

        $contextKeys = array();
        $contexts = $this->modx->getCollection('modContext', array('key:!=' => 'mgr'));
        foreach ($contexts as $context) {
            $contextKeys[] = $context->get('key');
        }

        $c = $this->modx->newQuery('modResource');
        $c->leftJoin('modTemplate', 'modTemplate', 'modResource.template = modTemplate.id');
        $c->select($this->modx->getSelectColumns('modResource', 'modResource'));
        $c->select("modTemplate.icon as icon");
        $c->where(array(
            array(
                'modResource.pagetitle:LIKE' => '%' . $this->query .'%',
                'OR:modResource.longtitle:LIKE' => '%' . $this->query .'%',
                'OR:modResource.alias:LIKE' => '%' . $this->query .'%',
                'OR:modResource.description:LIKE' => '%' . $this->query .'%',
                'OR:modResource.introtext:LIKE' => '%' . $this->query .'%',
                'OR:modResource.id:=' => $this->query,
            ),
            array(
                'modResource.context_key:IN' => $contextKeys,
            )
        ));
        $c->sortby('modResource.createdon', 'DESC');

        $c->limit($this->maxResults);

        $collection = $this->modx->getCollection('modResource', $c);
        /** @var modResource $record */
        foreach ($collection as $record) {
            $this->results[] = array(
                'name' => $this->modx->hasPermission('tree_show_resource_ids') ? $record->get('pagetitle') . ' (' . $record->get('id') . ')' : $record->get('pagetitle'),
                '_action' => 'resource/update&id=' . $record->get('id'),
                'description' => $record->get('description'),
                'type' => $type,
                'class' => $record->get('class_key'),
                'type_label' => $typeLabel,
                'icon' => str_replace('icon-', '', $record->get('icon'))
            );
        }
    }

    public function searchSnippets()
    {
        $type = 'snippets';

        $c = $this->modx->newQuery('modSnippet');
        $c->where(array(
            'name:LIKE' => '%' . $this->query . '%',
            'OR:description:LIKE' => '%' . $this->query .'%',
            'OR:id:=' => $this->query,
        ));

        $c->limit($this->maxResults);

        $collection = $this->modx->getCollection('modSnippet', $c);
        /** @var modSnippet $record */
        foreach ($collection as $record) {
            $this->results[] = array(
                'name' => $record->get('name'),
                '_action' => 'element/snippet/update&id=' . $record->get('id'),
                'description' => $record->get('description'),
                'type' => $type,
            );
        }
    }

    public function searchChunks()
    {
        $type = 'chunks';

        $class = 'modChunk';
        $c = $this->modx->newQuery($class);
        $c->where(array(
            'name:LIKE' => '%' . $this->query . '%',
            'OR:description:LIKE' => '%' . $this->query .'%',
            'OR:id:=' => $this->query,
        ));

        $c->limit($this->maxResults);

        $collection = $this->modx->getCollection($class, $c);
        /** @var modChunk $record */
        foreach ($collection as $record) {
            $this->results[] = array(
                'name' => $record->get('name'),
                '_action' => 'element/chunk/update&id=' . $record->get('id'),
                'description' => $record->get('description'),
                'type' => $type,
            );
        }
    }

    public function searchTemplates()
    {
        $type = 'templates';

        $class = 'modTemplate';
        $c = $this->modx->newQuery($class);
        $c->where(array(
            'templatename:LIKE' => '%' . $this->query . '%',
            'OR:description:LIKE' => '%' . $this->query .'%',
            'OR:id:=' => $this->query,
        ));

        $c->limit($this->maxResults);

        $collection = $this->modx->getCollection($class, $c);
        /** @var modTemplate $record */
        foreach ($collection as $record) {
            $this->results[] = array(
                'name' => $record->get('templatename'),
                '_action' => 'element/template/update&id=' . $record->get('id'),
                'description' => $record->get('description'),
                'type' => $type,
            );
        }
    }

    public function searchPlugins()
    {
        $type = 'plugins';

        $class = 'modPlugin';
        $c = $this->modx->newQuery($class);
        $c->where(array(
            'name:LIKE' => '%' . $this->query . '%',
            'OR:description:LIKE' => '%' . $this->query .'%',
            'OR:id:=' => $this->query,
        ));

        $c->limit($this->maxResults);

        $collection = $this->modx->getCollection($class, $c);
        /** @var modPlugin $record */
        foreach ($collection as $record) {
            $this->results[] = array(
                'name' => $record->get('name'),
                '_action' => 'element/plugin/update&id=' . $record->get('id'),
                'description' => $record->get('description'),
                'type' => $type,
            );
        }
    }

    public function searchTVs()
    {
        $type = 'tvs';

        $class = 'modTemplateVar';
        $c = $this->modx->newQuery($class);
        $c->where(array(
            'name:LIKE' => '%' . $this->query . '%',
            'OR:caption:LIKE' => '%' . $this->query .'%',
            'OR:id:=' => $this->query,
        ));

        $c->limit($this->maxResults);

        $collection = $this->modx->getCollection($class, $c);
        /** @var modTemplate $record */
        foreach ($collection as $record) {
            $this->results[] = array(
                'name' => $record->get('name'),
                '_action' => 'element/tv/update&id=' . $record->get('id'),
                'description' => $record->get('caption'),
                'type' => $type,
            );
        }
    }

    public function searchUsers()
    {
        $type = 'users';

        $class = 'modUser';
        $c = $this->modx->newQuery($class);
        $c->select(array(
            $this->modx->getSelectColumns($class, $class),
            $this->modx->getSelectColumns('modUserProfile', 'Profile', ''),
        ));
        $c->leftJoin('modUserProfile', 'Profile');
        $c->where(array(
            'username:LIKE' => '%' . $this->query . '%',
            'OR:Profile.fullname:LIKE' => '%' . $this->query .'%',
            'OR:Profile.email:LIKE' => '%' . $this->query .'%',
            'OR:id:=' => $this->query,
        ));

        $c->limit($this->maxResults);

        /** @var modUserProfile[] $collection */
        $collection = $this->modx->getCollection($class, $c);

        foreach ($collection as $record) {
            $this->results[] = array(
                'name' => $record->get('username'),
                '_action' => 'security/user/update&id=' . $record->get('internalKey'),
                'description' => $record->get('fullname') .' / '. $record->get('email'),
                'type' => $type,
            );
        }
    }
}

return 'modSearchProcessor';
