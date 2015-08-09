<?php

/**
 * Class modSearchProcessor
 * Searches for elements (all but plugins) & resources
 **/
class modSearchProcessor extends modProcessor
{
    protected $rawQuery;
    public $maxResults = 5;
    public $results = array();
    public $elements = array();

    public $mode = 'simple';
    public $searchElement = ''; // "r"
    public $searchField = ''; // "*"
    public $searchQuery = '';
    public $searchPage = 1;

    protected $debug;
    protected $btnClasses = "x-btn x-btn-small x-btn-icon-small-left primary-button x-btn-noicon";
    protected $sqlQuery;

    /**
     * Initializes the processor by loading searchable elements and lexicons
     *
     * @return boolean
     */
    public function initialize()
    {
        $this->modx->lexicon->load('core:topmenu', 'core:uberbar');
        $this->_getElements();
        $this->debug = $this->modx->getOption('uberbar_show_query', null, false);
        $this->mode = $this->modx->getOption('uberbar_mode', null, 'simple', true);

        return parent::initialize();
    }

    /**
     * @return string JSON formatted results
     */
    public function process()
    {
        $this->rawQuery = $this->getProperty('query');
        if (!empty($this->rawQuery)) {
            $this->prepareQuery();

            if (empty($this->searchElement)) {
                return $this->enterElement();
            }

            if (empty($this->searchField)) {
                return $this->enterField();
            }

            if (empty($this->searchQuery)) {
                return $this->enterQuery();
            }


            return $this->search();
        }

        return $this->outputArray($this->results);
    }

    /**
     * Executes the actual search
     */
    public function search()
    {
        $startTime = microtime(true);
        $options = '';

        $class_key = $this->elements[$this->searchElement]['class_key'];
        $fields = $this->elements[$this->searchElement]['fields'];

        // Prepare query
        $c = $this->modx->newQuery($class_key);

        // Some extra adjustments to search for users
        if ($class_key == 'modUser') {
            if (!in_array($this->searchField, array('username', '*'))) {
                $this->searchField = 'Profile.' . $this->searchField;
            }
            $c->select(array(
                $this->modx->getSelectColumns('modUser', 'modUser'),
                $this->modx->getSelectColumns('modUserProfile', 'Profile', ''),
            ));
            $c->leftJoin('modUserProfile', 'Profile');
        }

        // Replace wildcards (*) with % if they are not preceeded by [ (tags)
        $this->searchQuery = preg_replace('/(?<!\[)\*/', '%', $this->searchQuery);

        $where = array();
        /* Search inside a field */
        if (!empty($this->searchField) AND $this->searchField != '*') {
            $like = (strpos($this->searchQuery, '%') !== false ) ? ':LIKE' : ':=';

            $where = array($this->searchField . $like => $this->searchQuery);
            $c->where($where);
        }

        /* Search inside all fields */
        else {
            $like = (strpos($this->searchQuery, '%') !== false ) ? ':LIKE' : ':=';

            $n = 0;
            foreach ($fields as $fieldname) {
                $n++;
                if ($class_key == 'modUser' AND $fieldname != 'username') {
                    if ($n == 1) {
                        $key = 'Profile.' . $fieldname . $like;
                    } else {
                        $key = 'OR:Profile.' . $fieldname . $like;
                    }
                } else {
                    if ($n == 1) {
                        $key = $fieldname . $like;

                    } else {
                        $key = 'OR:' . $fieldname . $like;
                    }
                }
                $where[$key] = $this->searchQuery;
            }
            $c->where($where);
        }

        $totalResults = $this->modx->getCount($class_key, $c);

        $offset = ($this->searchPage - 1) * $this->maxResults;
        $start = ($totalResults) ? $offset + 1 : 0;

        $pages = ceil($totalResults / $this->maxResults);

        $header = $this->modx->lexicon('uberbar_searchresults_header');
        if ($pages > 1) {
            for ($page = 1; $page <= $pages; $page++) {
                $activeClass = ($page == $this->searchPage) ? 'active ' : '';
                $options .= '<li><a data-value="' . $page . '" class="' . $activeClass . $this->btnClasses . '">' . $page . '</a></li>';
            }

        }

        $exec = number_format(microtime(true) - $startTime, 3);

        if ($totalResults > 0) {
            $msg = $this->modx->lexicon('uberbar_searchresults_yes', array('totalResults' => $totalResults, 'exec' => $exec));
        } else {
            $msg = $this->modx->lexicon('uberbar_searchresults_no', array('your_query' => $this->rawQuery));
        }

        $c->limit($this->maxResults, $offset);

        if ($this->debug) {
            $c->prepare();
            $msg .= $c->toSQL();
        }

        /** @var xPDOObject[] $collection */
        $collection = $this->modx->getCollection($class_key, $c);


        $results = array();
        $results[] = array(
            'uberbar_header' => $this->modx->lexicon('uberbar_header'),
            'msg' => $msg,
            'options' => $options,
            'header' => $header,
            'id' => '',
            'name' => '',
            'description' => '',
            'content' => '',
            'icon' => '',
            '_action' => '',
            'type' => '',
            'current' => ''
        );

        foreach ($collection as $record) {

            $id = $record->get('id');
            $name = $record->get($this->elements[$this->searchElement]['name']);
            $description = $record->get($this->elements[$this->searchElement]['description']);
            $content = $record->get($this->searchField);

            $icon = $this->elements[$this->searchElement]['icon'];
            $action = $this->elements[$this->searchElement]['action'];
            $type = $this->elements[$this->searchElement]['type'];

            if (in_array($this->searchField, array('plugincode', 'snippet', 'content', 'introtext'))) {
                // find line where $search can be found
                $lines = explode("\n", $content);
                $content = '';
                $search = str_replace('%', '', $this->searchQuery);

                $i = 0;
                foreach ($lines as $linenumber => $line) {
                    $i++;
                    if (is_numeric(stripos($line, $search))) {
                        $line = htmlspecialchars($line);
                        $linenumber = str_pad($linenumber + 1, 3, '0', STR_PAD_LEFT);
                        $line = str_replace($search, '<strong>' . $search . '</strong>', $line);
                        $content .= "<pre class='line'><span class='number'>$linenumber</span><code>$line</code></pre>";
                    }
                }
            } else {
                $content = str_replace($this->searchQuery, "<strong>{$this->searchQuery}</strong>", $content);
            }

            $results[] = array(
                'uberbar_header' => $this->modx->lexicon('uberbar_header'),
                'msg' => '',
                'options' => '',
                'header' => '',
                'id' => $id,
                'name' => $name,
                'description' => $description,
                'content' => $content,
                'icon' => $icon,
                '_action' => $action . '&id=' . $id,
                'type' => $type,
                'current' => $start++
            );
        }
        return $this->outputArray($results);
    }

    /**
     * Collect a list of elements that can be searched, and their token to search by in the uberbar.
     *
     * Each element needs to define:
     * - label
     * - type
     * - class_key: the xPDO object class
     * - permission: boolean if the user has permission for this type of object
     * - action: where to go in the manager to edit the element
     * - fields: an array of field names that can be searched in simple mode
     * - name: the name field to use in the uberbar results
     * - description: the description field to use in the uberbar results
     * - content: the field that contains the content, can be shown in the results
     * - icon: a font awesome icon to use for results of this type
     */
    protected function _getElements()
    {
        $this->elements['s'] = array(
            'label' => '(s)nippet',
            'type' => 'snippets',
            'class_key' => 'modSnippet',
            'permission' => $this->modx->hasPermission('view_snippet'),
            'action' => 'element/snippet/update',
            'fields' => array(
                'id',
                'name',
                'description',
                'snippet'
            ),
            'name' => 'name',
            'description' => 'description',
            'content' => 'snippet',
            'icon' => 'code'
        );
        /* Chunks */
        $this->elements['c'] = array(
            'label' => '(c)hunk',
            'type' => 'chunks',
            'class_key' => 'modChunk',
            'permission' => $this->modx->hasPermission('view_chunk'),
            'action' => 'element/chunk/update',
            'fields' => array(
                'id',
                'name',
                'description',
                'snippet'
            ),
            'name' => 'name',
            'description' => 'description',
            'content' => 'snippet',
            'icon' => 'th'
        );
        /* Templates */
        $this->elements['t'] = array(
            'label' => '(t)emplate',
            'type' => 'templates',
            'class_key' => 'modTemplate',
            'permission' => $this->modx->hasPermission('view_template'),
            'action' => 'element/template/update',
            'fields' => array(
                'id',
                'templatename',
                'description',
                'content'
            ),
            'name' => 'templatename',
            'description' => 'description',
            'content' => 'content',
            'icon' => 'columns'
        );
        /* Plugins */
        $this->elements['p'] = array(
            'label' => '(p)lugin',
            'type' => 'plugins',
            'class_key' => 'modPlugin',
            'permission' => $this->modx->hasPermission('view_plugin'),
            'action' => 'element/plugin/update',
            'fields' => array(
                'id',
                'name',
                'description',
                'plugincode'
            ),
            'name' => 'name',
            'description' => 'description',
            'content' => 'plugincode',
            'icon' => 'cogs'
        );
        /* Template variables */
        $this->elements['tv'] = array(
            'label' => '(tv) templateVariable',
            'type' => 'tvs',
            'class_key' => 'modTemplateVar',
            'permission' => $this->modx->hasPermission('view_tv'),
            'action' => 'element/tv/update',
            'fields' => array(
                'name',
                'id',
                'caption',
                'description'
            ),
            'name' => 'name',
            'description' => 'description',
            'content' => false,
            'icon' => 'list-alt'
        );
        /* resources */
        $this->elements['r'] = array(
            'label' => '(r)esource',
            'type' => 'resources',
            'class_key' => 'modResource',
            'permission' => $this->modx->hasPermission('view_document'),
            'action' => 'resource/update',
            'fields' => array(
                'id',
                'pagetitle',
                'longtitle',
                'description',
                'introtext',
                'content',
                'template',
                'alias',
                'menutitle',
                'link_attributes',
                'hidemenu',
                'published',
                'deleted',
                'parent',
                'class_key',
                'content_type',
                'content_dispo'
            ),
            'name' => 'pagetitle',
            'description' => 'longtitle',
            'content' => 'content',
            'icon' => 'file-o'
        );
        /* user */
        $this->elements['u'] = array(
            'label' => '(u)ser',
            'type' => 'users',
            'class_key' => 'modUser',
            'permission' => $this->modx->hasPermission('view_user'),
            'action' => 'security/user/update',
            'fields' => array(
                'username',
                'fullname',
                'email',
                'blocked',
                'username',
                'fullname',
                'dob',
                'gender',
                'photo',
                'email',
                'phone',
                'mobilephone',
                'fax',
                'website',
                'blocked',
                'blockeduntil',
                'blockedafter',
                'failedlogincount',
                'logincount',
                'lastlogin',
                'thislogin',
                'sessionid',
                'address',
                'country',
                'city',
                'state',
                'zip',
                'comment',
                'extended'
            ),
            'name' => 'username',
            'description' => 'email',
            'content' => 'extended',
            'icon' => 'user'
        );
    }

    public function prepareQuery()
    {
        if (strpos($this->rawQuery, ':') !== 0) {
            // By default only search in resources
            $this->rawQuery = ':r:*:' . $this->rawQuery;
        }

        $query = trim($this->rawQuery, ':');
        $query = explode(':', $query);

        // The search element is the first part of the query string, and needs to match one
        // of the elements we have available.
        if (isset($query[0]) && array_key_exists($query[0], $this->elements)) {
            $this->searchElement = $query[0];
        }

        // The second part of the query string is the field we need to search in,
        // which can be * or a specific field that the element allows.
        if (isset($query[1]) && !empty($query[1])) {
            if ($query[1] === '*') {
                $this->searchField = '*';
            } elseif ($this->mode === 'simple' && in_array($query[1],
                    $this->elements[$this->searchElement]['fields'])
            ) {
                $this->searchField = $query[1];
            } elseif ($this->mode === 'advanced') { //@todo Perhaps check if the field exists on the object here
                $this->searchField = $query[1];
            }
        }

        // The third part of the query string is the actual value we're searching for.
        if (isset($query[2]) && !empty($query[2])) {
            $value = $query[2];
            // We do a loose search by default, so unless someone specified a wildcard, we wrap it in *'s.
            if (strpos($value, '*') === false) {
                $value = '*' . $value . '*';
            }
            $this->searchQuery = $value;
        }

        // Finally, the 4th part is the page number
        if (isset($query[3]) && is_numeric($query[3])) {
            $this->searchPage = (int)$query[3];
        }
    }

    /**
     * Shows instructions to choose an element to search in.
     *
     * @return string
     */
    public function enterElement()
    {
        $result = array();

        // Return list of available elements
        $result['header'] = $this->modx->lexicon('uberbar_pick_element_header');
        $result['msg'] = $this->modx->lexicon('uberbar_pick_element_msg', array('uberbar_mode' => $this->mode));
        $options = '';
        foreach ($this->elements as $key => $val) {
            /* Only show elements you have permission to see. */
            if ($val['permission'] === true) {
                $activeClass = ($key === $this->searchElement) ? 'active ' : '';
                $options .= '<li><a data-value="' . $key . '" class="' . $activeClass . $this->btnClasses . '">' . $val['label'] . '</a></li>';
            }
        }
        $result['options'] = $options;
        return $this->outputArray(array($result));
    }

    /**
     * Shows instructions to choose a field to search in, specific to the element.
     *
     * @return string
     */
    public function enterField()
    {
        $result = array();

        $result['header'] = $this->modx->lexicon('uberbar_pick_fieldname_header');
        $result['msg'] = $this->modx->lexicon('uberbar_pick_fieldname_msg',
            array('uberbar_mode' => $this->mode));

        $options = '';
        $options .= '<li><a data-value="*" class="' . $this->btnClasses . '">All (*)</a></li>';

        if ($this->mode == 'simple') {
            $fields = $this->elements[$this->searchElement]['fields'];
        } else {
            $fields = array_keys($this->modx->getFields($this->elements[$this->searchElement]['class_key']));
        }

        foreach ($fields as $key => $value) {
            $activeClass = ($value === $this->searchField) ? 'active ' : '';
            $options .= '<li><a data-value="' . $value . '" class="' . $activeClass . $this->btnClasses . '">' . $value . '</a></li>';
        }
        $result['options'] = $options;

        return $this->outputArray(array($result));
    }

    /**
     * Shows instructions to enter the actual search query / search string
     *
     * @return string
     */
    public function enterQuery()
    {
        $result = array();
        $result['header'] = $this->modx->lexicon('uberbar_enter_searchstring_header');
        $result['msg'] = $this->modx->lexicon('uberbar_enter_searchstring_msg');
        $result['options'] = '';
        return $this->outputArray(array($result));
    }

    /**
     * Dummy method to micmic actions search
     */
    /*
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
    }

    private function filterActions($query)
    {
        // source : http://stackoverflow.com/questions/5808923/filter-values-from-an-array-similar-to-sql-like-search-using-php
        $query = preg_quote($query, '~');
        $names = array();
        // $actions = array();
        // $descriptions = array();
        foreach ($this->actions as $idx => $action) {
            $names[$idx] = $action['name'];
            // $actions[$idx] = $action['action'];
            // $descriptions[$idx] = $action['description'];
        }
        $results = preg_grep('~' . $query . '~', $names);
        // $results = array_merge($results, preg_grep('~' . $this->query . '~', $actions));
        // $results = array_merge($results, preg_grep('~' . $this->query . '~', $descriptions));

        //$output = array();
        if ($results) {
            foreach ($results as $idx => $field) {
                $this->results[] = $this->actions[$idx];
            }
        }

        //$output = array_unique($output);

        //return $output;
    }
    */
}

return 'modSearchProcessor';
