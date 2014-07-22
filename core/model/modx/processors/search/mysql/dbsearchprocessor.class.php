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
    public function searchResources()
    {
        $type = 'resources';
        $idxFields = $this->modx->getSelectColumns('modResource', '', '', array('pagetitle', 'longtitle', 'description', 'introtext', 'alias'));
        $typeLabel = $this->modx->lexicon('search_resulttype_' . $type);

        $sql = "SELECT {$this->modx->escape('id')} AS {$this->modx->escape('modResource_id')},
                        {$this->modx->escape('pagetitle')} AS {$this->modx->escape('modResource_pagetitle')},
                        {$this->modx->escape('description')} AS {$this->modx->escape('modResource_description')},
                MATCH ($idxFields) AGAINST (:search IN BOOLEAN MODE) AS {$this->modx->escape('score')}
            FROM {$this->modx->getTableName('modResource')}
            WHERE MATCH ($idxFields) AGAINST (:search IN BOOLEAN MODE)
            ORDER BY {$this->modx->escape('score')} DESC
            LIMIT {$this->maxResults}";
        $c = new xPDOCriteria($this->modx, $sql, array(':search' => $this->query));
        $c->prepare();

        $collection = $this->modx->getCollection('modResource', $c);
        $results = array();
        /** @var modResource $record */
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

        $idxFields = $this->modx->getSelectColumns('modSnippet', '', '', array('name', 'description'));
        $typeLabel = $this->modx->lexicon('search_resulttype_' . $type);


        $sql = "SELECT {$this->modx->escape('id')} AS {$this->modx->escape('modSnippet_id')},
                        {$this->modx->escape('name')} AS {$this->modx->escape('modSnippet_name')},
                        {$this->modx->escape('description')} AS {$this->modx->escape('modSnippet_description')},
                MATCH ($idxFields) AGAINST (:search IN BOOLEAN MODE) AS {$this->modx->escape('score')}
            FROM {$this->modx->getTableName('modSnippet')}
            WHERE MATCH ($idxFields) AGAINST (:search IN BOOLEAN MODE)
            ORDER BY {$this->modx->escape('score')} DESC
            LIMIT {$this->maxResults}";
        $c = new xPDOCriteria($this->modx, $sql, array(':search' => $this->query));
        $c->prepare();

        $collection = $this->modx->getCollection('modSnippet', $c);
        $results = array();
        /** @var modSnippet $record */
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

        $typeLabel = $this->modx->lexicon('search_resulttype_' . $type);
        $idxFields = $this->modx->getSelectColumns('modChunk', '', '', array('name', 'description'));

        $sql = "SELECT {$this->modx->escape('id')} AS {$this->modx->escape('modChunk_id')},
                        {$this->modx->escape('name')} AS {$this->modx->escape('modChunk_name')},
                        {$this->modx->escape('description')} AS {$this->modx->escape('modChunk_description')},
                MATCH ($idxFields) AGAINST (:search IN BOOLEAN MODE) AS {$this->modx->escape('score')}
            FROM {$this->modx->getTableName('modChunk')}
            WHERE MATCH ($idxFields) AGAINST (:search IN BOOLEAN MODE)
            ORDER BY {$this->modx->escape('score')} DESC
            LIMIT {$this->maxResults}";
        $c = new xPDOCriteria($this->modx, $sql, array(':search' => $this->query));
        $c->prepare();
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

        $idxFields = $this->modx->getSelectColumns('modTemplate', '', '', array('templatename', 'description'));
        $sql = "SELECT {$this->modx->escape('id')} AS {$this->modx->escape('modTemplate_id')},
                        {$this->modx->escape('templatename')} AS {$this->modx->escape('modTemplate_templatename')},
                        {$this->modx->escape('description')} AS {$this->modx->escape('modTemplate_description')},
                MATCH ($idxFields) AGAINST (:search IN BOOLEAN MODE) AS {$this->modx->escape('score')}
            FROM {$this->modx->getTableName('modTemplate')}
            WHERE MATCH ($idxFields) AGAINST (:search IN BOOLEAN MODE)
            ORDER BY {$this->modx->escape('score')} DESC
            LIMIT {$this->maxResults}";
        $c = new xPDOCriteria($this->modx, $sql, array(':search' => $this->query));
        $c->prepare();

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

        $idxFields = $this->modx->getSelectColumns('modPlugin', '', '', array('name', 'description'));

        $sql = "SELECT {$this->modx->escape('id')} AS {$this->modx->escape('modPlugin_id')},
                        {$this->modx->escape('name')} AS {$this->modx->escape('modPlugin_name')},
                        {$this->modx->escape('description')} AS {$this->modx->escape('modPlugin_description')},
                MATCH ($idxFields) AGAINST (:search IN BOOLEAN MODE) AS {$this->modx->escape('score')}
            FROM {$this->modx->getTableName('modPlugin')}
            WHERE MATCH ($idxFields) AGAINST (:search IN BOOLEAN MODE)
            ORDER BY {$this->modx->escape('score')} DESC
            LIMIT {$this->maxResults}";
        $c = new xPDOCriteria($this->modx, $sql, array(':search' => $this->query));
        $c->prepare();
        $collection = $this->modx->getCollection($class, $c);
        /** @var modPlugin $record */
        $results = array();
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

        $idxFields = $this->modx->getSelectColumns('modPlugin', '', '', array('name', 'caption', 'description'));

        $sql = "SELECT {$this->modx->escape('id')} AS {$this->modx->escape('modTemplateVar_id')},
                        {$this->modx->escape('name')} AS {$this->modx->escape('modTemplateVar_name')},
                        {$this->modx->escape('caption')} AS {$this->modx->escape('modTemplateVar_caption')},
                MATCH ($idxFields) AGAINST (:search IN BOOLEAN MODE) AS {$this->modx->escape('score')}
            FROM {$this->modx->getTableName('modTemplateVar')}
            WHERE MATCH ($idxFields) AGAINST (:search IN BOOLEAN MODE)
            ORDER BY {$this->modx->escape('score')} DESC
            LIMIT {$this->maxResults}";
        $c = new xPDOCriteria($this->modx, $sql, array(':search' => $this->query));
        $c->prepare();

        $collection = $this->modx->getCollection($class, $c);
        /** @var modTemplate $record */
        $results = array();
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

        $idxFields = $this->modx->getSelectColumns('modUser', 'modUser', '', array('username'));
        $idxFields2 = $this->modx->getSelectColumns('modUserProfile', 'profile', '', array('fullname', 'email'));
        // Cheating xPDO a bit in this query
        $sql = "SELECT {$this->modx->escape('modUser')}.{$this->modx->escape('id')} AS {$this->modx->escape('modUser_id')},
                        {$this->modx->escape('modUser')}.{$this->modx->escape('username')} AS {$this->modx->escape('modUser_username')},
                        {$this->modx->escape('profile')}.{$this->modx->escape('email')} AS {$this->modx->escape('modUser_email')},
                        {$this->modx->escape('profile')}.{$this->modx->escape('fullname')} AS {$this->modx->escape('modUser_fullname')},
                MATCH ($idxFields) AGAINST (:search IN BOOLEAN MODE) AS {$this->modx->escape('score')}
            FROM {$this->modx->getTableName('modUser')} {$this->modx->escape('modUser')}
            INNER JOIN {$this->modx->getTableName('modUserProfile')}  {$this->modx->escape('profile')} ON
              {$this->modx->escape('profile')}.{$this->modx->escape('internalKey')} =
              {$this->modx->escape('modUser')}.{$this->modx->escape('id')}
            WHERE MATCH ($idxFields) AGAINST (:search IN BOOLEAN MODE) OR
              MATCH ($idxFields2) AGAINST (:search IN BOOLEAN MODE)
            ORDER BY {$this->modx->escape('score')} DESC
            LIMIT {$this->maxResults}";
        $c = new xPDOCriteria($this->modx, $sql, array(':search' => $this->query));
        $c->prepare();

        $collection = $this->modx->getCollection($class, $c);
        /** @var modUserProfile $record */
        $results = array();
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