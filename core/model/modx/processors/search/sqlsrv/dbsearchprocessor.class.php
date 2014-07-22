<?php

class dbSearchProcessor {
    public $query = null;
    public $maxResults = 5;
    public $modx = null;

    public function __construct(modX &$modx, $options = array()) {
        $this->modx = $modx;
        $this->query = $options['query'];
        $this->maxResults = $options['max'];
    }

    /**
     * Perform search in resources
     *
     * @return void
     */
    /**
     * Perform search in resources
     *
     * @return void
     */
    public function searchResources()
    {
        $type = 'resources';
        $typeLabel = $this->modx->lexicon('search_resulttype_' . $type);

        $c = $this->modx->newQuery('modResource');
        $c->where(array(
            'pagetitle:LIKE' => '%' . $this->query .'%',
            'OR:longtitle:LIKE' => '%' . $this->query .'%',
            'OR:alias:LIKE' => '%' . $this->query .'%',
            'OR:description:LIKE' => '%' . $this->query .'%',
            'OR:introtext:LIKE' => '%' . $this->query .'%',
        ));
        $c->sortby('createdon', 'DESC');

        $c->limit($this->maxResults);

        $collection = $this->modx->getCollection('modResource', $c);
        /** @var modResource $record */
        $results = array();
        foreach ($collection as $record) {
            $results[] = array(
                'name' => $record->get('pagetitle'),
                '_action' => 'resource/update&id=' . $record->get('id'),
                'description' => $record->get('description'),
                'type' => $type,
                'type_label' => $typeLabel,
            );
        }
        return $results;
    }

    public function searchSnippets()
    {
        $type = 'snippets';

        $c = $this->modx->newQuery('modSnippet');
        $c->where(array(
            'name:LIKE' => '%' . $this->query . '%',
            'OR:description:LIKE' => '%' . $this->query .'%',
        ));

        $c->limit($this->maxResults);

        $collection = $this->modx->getCollection('modSnippet', $c);
        /** @var modSnippet $record */
        $results = array();
        foreach ($collection as $record) {
            $results[] = array(
                'name' => $record->get('name'),
                '_action' => 'element/snippet/update&id=' . $record->get('id'),
                'description' => $record->get('description'),
                'type' => $type,
            );
        }
        return $results;
    }

    public function searchChunks()
    {
        $type = 'chunks';

        $class = 'modChunk';
        $c = $this->modx->newQuery($class);
        $c->where(array(
            'name:LIKE' => '%' . $this->query . '%',
            'OR:description:LIKE' => '%' . $this->query .'%',
        ));

        $c->limit($this->maxResults);

        $collection = $this->modx->getCollection($class, $c);

        $results = array();
        /** @var modChunk $record */
        foreach ($collection as $record) {
            $results[] = array(
                'name' => $record->get('name'),
                '_action' => 'element/chunk/update&id=' . $record->get('id'),
                'description' => $record->get('description'),
                'type' => $type,
            );
        }
        return $results;
    }

    public function searchTemplates()
    {
        $type = 'templates';

        $class = 'modTemplate';
        $c = $this->modx->newQuery($class);
        $c->where(array(
            'templatename:LIKE' => '%' . $this->query . '%',
            'OR:description:LIKE' => '%' . $this->query .'%',
        ));

        $c->limit($this->maxResults);

        $collection = $this->modx->getCollection($class, $c);
        $results = array();
        /** @var modTemplate $record */
        foreach ($collection as $record) {
            $results[] = array(
                'name' => $record->get('templatename'),
                '_action' => 'element/template/update&id=' . $record->get('id'),
                'description' => $record->get('description'),
                'type' => $type,
            );
        }
        return $results;
    }

    public function searchPlugins()
    {
        $type = 'plugins';

        $class = 'modPlugin';
        $c = $this->modx->newQuery($class);
        $c->where(array(
            'name:LIKE' => '%' . $this->query . '%',
            'OR:description:LIKE' => '%' . $this->query .'%',
        ));

        $c->limit($this->maxResults);

        $collection = $this->modx->getCollection($class, $c);
        $results = array();
        /** @var modPlugin $record */
        foreach ($collection as $record) {
            $results[] = array(
                'name' => $record->get('name'),
                '_action' => 'element/plugin/update&id=' . $record->get('id'),
                'description' => $record->get('description'),
                'type' => $type,
            );
        }
        return $results;
    }

    public function searchTVs()
    {
        $type = 'tvs';

        $class = 'modTemplateVar';
        $c = $this->modx->newQuery($class);
        $c->where(array(
            'name:LIKE' => '%' . $this->query . '%',
            'OR:caption:LIKE' => '%' . $this->query .'%',
        ));

        $c->limit($this->maxResults);

        $collection = $this->modx->getCollection($class, $c);
        $results = array();
        /** @var modTemplate $record */
        foreach ($collection as $record) {
            $results[] = array(
                'name' => $record->get('name'),
                '_action' => 'element/tv/update&id=' . $record->get('id'),
                'description' => $record->get('caption'),
                'type' => $type,
            );
        }
        return $results;
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
        ));

        $c->limit($this->maxResults);

        $collection = $this->modx->getCollection($class, $c);
        $results = array();
        /** @var modUserProfile $record */
        foreach ($collection as $record) {
            $results[] = array(
                'name' => $record->get('username'),
                '_action' => 'security/user/update&id=' . $record->get('id'),
                'description' => $record->get('fullname') .' / '. $record->get('email'),
                'type' => $type,
            );
        }
        return $results;
    }
}