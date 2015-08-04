<?php

/**
 * Class modSearchProcessor
 * Searches for elements (all but plugins) & resources
 **/
class modSearchProcessor extends modProcessor
{
    protected $query;
    public $maxResults = 5;
    public $results = array();
    public $elements = array();

    protected $debug;

    /**
     * Initializes the processor by loading searchable elements and lexicons
     *
     * @return boolean
     */
    public function initialize()
    {
        $this->modx->lexicon->load('core:topmenu', 'core:uberbar');
        $this->_getElements();
        $this->debug = false; // @todo maybe add this to a setting, or evaluate if it's needed at all

        return parent::initialize();
    }

    /**
     * @return string JSON formatted results
     */
    public function process()
    {
        $this->query = $this->getProperty('query');

        if (!empty($this->query)) {
            if (strpos($this->query, ':') !== 0) {
                // By default only search in resources
                $this->query = ':r:*:' . $this->query;
            }
            $this->query = explode(':', $this->query);
            $this->doSearch();
        }

        return $this->outputArray($this->results);
    }

    public function doSearch()
    {
        $query = $this->query;
        /* What are we looking for? */
        $element = isset($query[1]) ? $query[1] : false;

        $count = count($query);
        $result['count'] = $count;

        $btn = "x-btn x-btn-small x-btn-icon-small-left primary-button x-btn-noicon";

        $result['uberbar_header'] = $this->modx->lexicon('uberbar_header');

        /* : */
        if ($count == 2) {
            $validate = $this->validateUberbar($query);

            if ($validate === true) {
                // Return list of available resource types
                $uberbar_mode = $this->modx->getOption('uberbar_mode');
                $result['header'] = $this->modx->lexicon('uberbar_pick_element_header');
                $result['msg'] = $this->modx->lexicon('uberbar_pick_element_msg', array('uberbar_mode' => $uberbar_mode));
                $options = '';
                foreach ($this->elements as $key => $val) {
                    /* Only show elements you have permission to see. */
                    if ($val['permission'] === true) {
                        $activeClass = ($key == $query[1]) ? 'active ' : '';
                        $options .= '<li><a data-value="' . $key . '" class="' . $activeClass . $btn . '">' . $val['label'] . '</a></li>';
                    }
                }
                $result['options'] = $options;

                $this->results[] = $result;
            } else {
                $this->results[] = $validate;
            }
        }

        /* :r: */
        if ($count == 3) {
            $validate = $this->validateUberbar($query);

            if ($validate === true) {
                // Return list of available fields that can be searched

                $result['header'] = $this->modx->lexicon('uberbar_pick_fieldname_header');
                $result['msg'] = $this->modx->lexicon('uberbar_pick_fieldname_msg',
                    array('uberbar_mode' => $uberbar_mode));

                $options = '';
                $options .= '<li><a data-value="*" class="' . $btn . '">All (*)</a></li>';

                if ($uberbar_mode == 'simple') {
                    $fields = $this->elements[$element]['fields'];
                } else {
                    $fields = array_keys($this->modx->getFields($this->elements[$element]['class_key']));
                }

                foreach ($fields as $key => $value) {
                    $activeClass = $value == $query[2] ? 'active ' : '';
                    $options .= '<li><a data-value="' . $value . '" class="' . $activeClass . $btn . '">' . $value . '</a></li>';
                }
                $result['options'] = $options;

                $this->results[] = $result;
            } else {
                $this->results[] = $validate;
            }
        }


        /* :r:pagetitle: */
        if ($count == 4) {
            $validate = $this->validateUberbar($query);

            if ($validate === true) {
                $result['header'] = $this->modx->lexicon('uberbar_enter_searchstring_header');
                $result['msg'] = $this->modx->lexicon('uberbar_enter_searchstring_msg');

                $result['options'] = '';

                $this->results[] = $result;
            } else {
                $this->results[] = $validate;
            }
        }

        /* :r:pagetitle:*test*: */
        if ($count == 5) {
            $validate = $this->validateUberbar($query);

            if ($validate === true) {
                // Process search query, paginate on demand
                if (!empty($this->elements[$element]['class_key'])) {
                    $this->queryElement($query);
                } else {
                    return;
                }

            } else {
                $this->results[] = $validate;
            }
        }

        if ($count > 5) {
            if ($validate === true) {
                $validate = $this->validateUberbar($query);
            } else {
                $this->results[] = $validate;
            }
        }
    }

    /**
     * Does a search among the various allowed elements (see $this->_getElements).
     *
     * @param $query
     */
    public function queryElement($query)
    {
        $startTime = microtime(true);

        $maxResults = $this->maxResults;
        $options = '';

        $btn = "x-btn x-btn-small x-btn-icon-small-left primary-button x-btn-noicon";

        $element = $query[1];
        $field = $query[2];
        $search = $query[3];

        $class_key = $this->elements[$element]['class_key'];
        $fields = $this->elements[$element]['fields'];

        // Prepare query
        $c = $this->modx->newQuery($class_key);

        // Some extra adjustments to search for users
        if ($class_key == 'modUser') {
            if ($field != 'username') {
                $field = 'Profile.' . $field;
            }
            $c->select(array(
                $this->modx->getSelectColumns('modUser', 'modUser'),
                $this->modx->getSelectColumns('modUserProfile', 'Profile', ''),
            ));
            $c->leftJoin('modUserProfile', 'Profile');
        }

        // Replace wildcards (*) with % if they are not preceeded by [ (@todo: or `)
        $search = preg_replace('/(?<!\[)\*/', '%', $search);

        $where = array();

        /* Search inside a field */
        if (!empty($field) AND $field != '*') {
            $like = ($search != $query[3]) ? ':LIKE' : ':=';

            $where = array($field . $like => $search);
            $c->where($where);
        }
        /* Search inside all fields */
        else {
            // @todo I don't get why := for the else clause doesn't work...
            $like = ($search != $query[3]) ? ':LIKE' : ':LIKE';

            $n = 0;
            foreach ($fields as $fieldname) {
                $n++;
                if ($class_key == 'modUser' AND $fieldname != 'username') {
                    if ($n == 1) {
                        $key = 'Profile.' . $fieldname . $like;
                    } else {
                        $key = 'OR:Profile' . $fieldname . $like;
                    }
                } else {
                    if ($n == 1) {
                        $key = $fieldname . $like;

                    } else {
                        $key = 'OR:' . $fieldname . $like;
                    }
                }
                $where[$key] = $search;
            }
            $c->where($where);
        }

        if ($this->debug === true) {
            $this->debug['q'] = '<br/>Full query: <pre style="color: #fff">' . print_r($where, true) . '</pre>';
        }

        $totalResults = $this->modx->getCount($class_key, $c);

        $offset = is_numeric($query[4]) && $query[4] > 0 ? ($query[4] - 1) * $maxResults : 0;
        $start = ($totalResults) ? $offset + 1 : 0;

        /*
         * @todo Seems unused, might be a bug or simply remnant of earlier iteration
         * if ($offset == 0) {
            $end = $maxResults > $totalResults ? $totalResults : $maxResults;
        } else {
            $end = ($offset * $query[4] < $totalResults) ? $offset * $query[4] : $totalResults;
        }*/


        $pages = ceil($totalResults / $maxResults);

        switch (count($query)) {
            case 4:
                $header = $this->modx->lexicon('uberbar_enter_searchstring_header');
                break;
            default:
                if ($totalResults > $maxResults) {
                    $activePage = !empty($query[4]) ? $query[4] : '1';
                    for ($page = 1; $page <= $pages; $page++) {
                        $activeClass = ($page == $activePage) ? 'active ' : '';
                        $options .= '<li><a data-value="' . $page . '" class="' . $activeClass . $btn . '">' . $page . '</a></li>';
                    }
                }
                $header = $this->modx->lexicon('uberbar_searchresults_header');
                break;
        }

        $exec = number_format(microtime(true) - $startTime, 3);

        if ($totalResults > 0) {
            //'Your search returned <strong>' . $totalResults . ' results </strong> in ' . $exec . ' seconds.';

            $msg = $this->modx->lexicon('uberbar_searchresults_yes',
                array('totalResults' => $totalResults, 'exec' => $exec));
        } else {
            //'Your search for <strong>' . . '</strong> returned <strong>0</strong> results. Try again.';
            $your_query = implode(':', $query);
            $msg = $this->modx->lexicon('uberbar_searchresults_yes', array('your_query' => $your_query));
        }

        $msg .= $this->debug['q'];

        $c->limit($this->maxResults, $offset);

        /** @var xPDOObject[] $collection */
        $collection = $this->modx->getCollection($class_key, $c);

        $this->results[] = array(
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

            if ($element == 'ss') {
                $id = $record->get('key');
                $name = $this->modx->lexicon('setting_' . $record->get('key'));
                $description = '';
                $content = $record->get('value');
            } else {
                $id = $record->get('id');
                $name = $record->get($this->elements[$element]['name']);
                $description = $record->get($this->elements[$element]['description']);
                $content = $record->get($field);
            }

            $icon = $this->elements[$element]['icon'];
            $action = $this->elements[$element]['action'];
            $type = $this->elements[$element]['type'];


            if (in_array($field, array('plugincode', 'snippet', 'content', 'introtext'))) {
                // find line where $search can be found
                $lines = explode("\n", $content);
                $content = '';
                $search = str_replace('%', '', $search);

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
                $content = str_replace($search, "<strong>$search</strong>", $content);
            }

            $this->results[] = array(
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
    }

    /**
     * Validates the uberbar to check for permissions, allowed elements and fields etc.
     *
     * @param $query
     * @return bool
     */
    public function validateUberbar($query)
    {
        $mode = $this->modx->getOption('uberbar_mode');
        $count = count($query);
        $element = isset($query[1]) ? $query[1] : false;
        $field = isset($query[2]) ? $query[2] : false;

        // Element related stuff...
        if ($count > 2) {
            /* Validate elements */
            if ($element != '*' && !array_key_exists($element, $this->elements)) {
                // Error: Unknown element
                $result['header'] = $this->modx->lexicon('uberbar_error_unknown_element_header');

                // You are searching for an unknown element: <strong>{element}</strong>;
                $result['msg'] = $this->modx->lexicon('uberbar_error_unknown_element_msg', array('element' => $element));

                $result['options'] = '';

                return $result;
            }
            /* Validate permissions */
            elseif ($this->elements[$element]['permission'] !== true) {
                // Error: Permission denied
                $result['header'] = $this->modx->lexicon('uberbar_error_permission_denied_header');

                // You are searching in an element you are not allowed to view: <strong>{element}</strong>.
                $result['msg'] = $this->modx->lexicon('uberbar_error_permission_denied_msg', array('element' => $element));
                $result['options'] = '';
                return $result;
            }
        }

        if ($count > 3) {
            // Validate fieldnames...
            if ($mode == 'simple') {
                $fields = $this->elements[$element]['fields'];
            } else {
                $fields = array_keys($this->modx->getFields($this->elements[$element]['class_key']));
            }


            if ($field != '*' AND in_array($field, $fields) !== true) {

                // Error: Unknown field
                $result['header'] = $this->modx->lexicon('uberbar_error_unknown_field_header');

                // You are searching inside an unknown field: <strong>{field}</strong>. Please remove it from your search and try something else.
                $result['msg'] = $this->modx->lexicon('uberbar_error_unknown_field_msg', array('field' => $field));
                $result['options'] = '';

                return $result;
            }
        }

        return true;
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
