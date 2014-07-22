<?php
/**
 * Class modSearchProcessor
 * Searches for elements (all but plugins) & resources
 **/

class modSearchProcessor extends modProcessor
{
    public $maxResults = 5;
    public $actionToken = ':';
    private $actions = array();
    private $searchDB = null;

    protected $query;
    public $results = array();

    /**
     * @return string JSON formatted results
     */
    public function process()
    {
        $this->query = $this->getProperty('query');
        $this->loadDB();
        if (!empty($this->query)) {
            if (strpos($this->query, ':') === 0) {
                // upcoming "launch actions"
                //$this->searchActions();
            } else {
                // Search elements & resources
                $this->results = array_merge($this->results, $this->searchDB->searchResources());
                if ($this->modx->hasPermission('view_chunk')) {
                    $this->results = array_merge($this->results, $this->searchDB->searchChunks());
                }
                if ($this->modx->hasPermission('view_template')) {
                    $this->results = array_merge($this->results, $this->searchDB->searchTemplates());
                }
                if ($this->modx->hasPermission('view_tv')) {
                    $this->results = array_merge($this->results, $this->searchDB->searchTVs());
                }
                if ($this->modx->hasPermission('view_snippet')) {
                    $this->results = array_merge($this->results, $this->searchDB->searchSnippets());
                }
                if ($this->modx->hasPermission('view_plugin')) {
                    $this->results = array_merge($this->results, $this->searchDB->searchPlugins());
                }
                if ($this->modx->hasPermission('view_user')) {
                    $this->results = array_merge($this->results, $this->searchDB->searchUsers());
                }
            }
        }
        return $this->outputArray($this->results);
    }

    public function loadDB() {

        $className = $this->modx->loadClass($this->modx->getOption('driver').'.dbSearchProcessor', dirname(__FILE__), true);
        $this->searchDB = new $className($this->modx, array(
            'query' => $this->query,
            'max' => $this->maxResults
        ));
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
}

return 'modSearchProcessor';
