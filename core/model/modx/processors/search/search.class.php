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

    protected $query;
    public $results = array();

    /**
     * @return string JSON formatted results
     */
    public function process()
    {
        $this->query = $this->getProperty('query');
        
        if (!empty($this->query)) {
            if (strpos($this->query, ':') === 0) {
                // Advanced Search/Actions
                
                $query = explode(':',$this->query);
                $this->advancedSearch($query);
                
            } else {
                // Default search only in resources
                $query = ':r:*:'.$this->query;
                
                $query = explode(':',$query);
                $this->advancedSearch($query);
            }
        }

        return $this->outputArray($this->results);
    }

    public function advancedSearch($query){
        
        $this->modx->lexicon->load('core:topmenu','core:uberbar');
        
        $uberbar_mode = $this->modx->getOption('uberbar_mode');
        
        $elements = array();
        
        if(1==1){ // Sorry, I need the codefolding...
            /* Snippets */
            $elements['s'] = array (
                'label'        => '(s)nippet'
               ,'type'         => 'snippets'
               ,'class_key'    => 'modSnippet'
               ,'permission'   => $this->modx->hasPermission('view_snippet')
               ,'action'       => 'element/snippet/update'
               ,'fields'       =>  array (
                    'id','name','description','snippet'
                )
               ,'name'         => 'name'
               ,'description'  => 'description'
               ,'content'      => 'snippet'
               ,'icon'         => 'code'
            );
            /* Chunks */
            $elements['c'] = array (
                'label'        => '(c)hunk'
               ,'type'         => 'chunks'
               ,'class_key'    => 'modChunk'
               ,'permission'   => $this->modx->hasPermission('view_chunk')
               ,'action'       => 'element/chunk/update'
               ,'fields'       =>  array (
                    'id','name','description','snippet'
                )
               ,'name'         => 'name'
               ,'description'  => 'description'
               ,'content'      => 'snippet'
               ,'icon'         => 'th'
            );
            /* Templates */
            $elements['t'] = array (
                'label'        => '(t)emplate'
               ,'type'         => 'templates'
               ,'class_key'    => 'modTemplate'
               ,'permission'   => $this->modx->hasPermission('view_template')
               ,'action'       => 'element/template/update'
               ,'fields'       =>  array (
                    'id','templatename','description','content'
                )
               ,'name'         => 'templatename'
               ,'description'  => 'description'
               ,'content'      => 'content'
               ,'icon'         => 'columns'
            );
            /* Plugins */
            $elements['p'] = array (
                'label'        => '(p)lugin'
               ,'type'         => 'plugins'
               ,'class_key'    => 'modPlugin'
               ,'permission'   => $this->modx->hasPermission('view_plugin')
               ,'action'       => 'element/plugin/update'
               ,'fields'       =>  array (
                    'id','name','description','plugincode'
                )
               ,'name'         => 'name'
               ,'description'  => 'description'
               ,'content'      => 'plugincode'
               ,'icon'         => 'cogs'
            );
            /* Template variables */
            $elements['tv'] = array(
                'label'        => '(tv) templateVariable'
               ,'type'         => 'tvs'
               ,'class_key'    => 'modTemplateVar'
               ,'permission'   => $this->modx->hasPermission('view_tv')
               ,'action'       => 'element/tv/update'
               ,'fields'       =>  array (
                    'name','id','caption','description'
                )
               ,'name'         => 'name'
               ,'description'  => 'description'
               ,'content'      =>  false
               ,'icon'         => 'list-alt'
            );
            /* resources */
            $elements['r'] = array(
                'label'        => '(r)esource'
               ,'type'         => 'resources'
               ,'class_key'    => 'modResource'
               ,'permission'   => $this->modx->hasPermission('view_document')
               ,'action'       => 'resource/update'
               ,'fields'       =>  array (
                     'id','pagetitle','longtitle','description','introtext','content'
                    ,'template','alias','menutitle','link_attributes'
                    ,'hidemenu','published','deleted'
                    ,'parent','class_key','content_type','content_dispo'
                )
               ,'name'         => 'pagetitle'
               ,'description'  => 'longtitle'
               ,'content'      => 'content'
               ,'icon'         => 'file-o'
            );
            /* user */
            $elements['u'] = array(
                'label'        => '(u)ser'
               ,'type'         => 'users'
               ,'class_key'    => 'modUser'
               ,'permission'   => $this->modx->hasPermission('view_user')
               ,'action'       => 'security/user/update'
               ,'fields'       =>  array (
                   'username','fullname','email','blocked'
                  ,'username','fullname','dob','gender','photo'
                  ,'email','phone','mobilephone','fax','website'
                  ,'blocked','blockeduntil','blockedafter'    ,'failedlogincount','logincount','lastlogin','thislogin','sessionid'
                  ,'address','country','city','state','zip'
                  ,'comment','extended'
                )
               ,'name'         => 'username'
               ,'description'  => 'email'
               ,'content'      => 'extended'
               ,'icon'         => 'user'
            );
            /*  system settings :x:ss:site_start: 
                2do: Doesn't really make sense before we can open system settings for a specific key...
            $elements['ss'] = array(
                 'label'       => '(ss) systemSetting'
                ,'type'        => ''
                ,'class_key'   => 'modSystemSetting'
                ,'permission'  => $this->modx->hasPermission('settings')
                ,'action'      => 'system/settings'
                ,'fields'      => array('key')
                ,'name'        => 'key'
                ,'description' => 'value'
                ,'content'     => 'value'
                ,'icon'        => 'gear'
                
            );
            */
            
        }     
        
        
        

    /* Basic UI: Messages and validation */
        $valid = false;
        $step1 = $step2 = $step3 = $step4 = $step5 = false;
    
        /* What are we looking for? */
        $start      = isset($query[0]) AND  empty($query) ? true : false;
        $element    = isset($query[1]) ? $query[1] : false;
        $field      = isset($query[2]) ? $query[2] : false;
        $value      = isset($query[3]) ? $query[3] : false;
        $page       = isset($query[4]) ? $query[4] : false;
        
        $count = count($query);
        $result['count'] = $count;

        $btn = "x-btn x-btn-small x-btn-icon-small-left primary-button x-btn-noicon";
        
        function validate_Uberbar($elements,$query){
            
            global $modx;
            
            $uberbar_mode = $modx->getOption('uberbar_mode');
            
            $count = count($query);

            $element    = isset($query[1]) ? $query[1] : false;
            $field      = isset($query[2]) ? $query[2] : false;
            $value      = isset($query[3]) ? $query[3] : false;
            $page       = isset($query[4]) ? $query[4] : false;
            
            // Element related stuff...
            if($count > 2)
            {
                /* Validate elements */
                if( $element != '*' && !array_key_exists($element,$elements) )
                {
                    // Error: Unknown element
                        $result['header'] = $modx->lexicon('uberbar_error_onknown_element_header');
                        
                    // You are searching for an unknown element: <strong>{element}</strong>;
                        $s = array('{element}');
                        $r = array($element);
                        $result['msg'] = str_replace( $s , $r, $modx->lexicon('uberbar_error_onknown_element_msg') );
                    
                    $result['options'] = '';
                    return $result;
                }                        
                /* Validate permissions */
                elseif( $elements[$element]['permission'] !== true)
                {
                    // Error: Permission denied
                        $result['header'] = $modx->lexicon('uberbar_error_permission_denied_header');
                    
                    // You are searching in an element you are not allowed to view: <strong>{element}</strong>.
                        $s = array('{element}');
                        $r = array($element);
                        $result['msg'] = str_replace($s , $r , $modx->lexicon('uberbar_error_permission_denied_msg') );
                        $result['options'] = '';
                        
                    return $result;
                }
            }

            if($count > 3)
            {
                // Validate fieldnames...
                if($uberbar_mode == 'simple')
                {
                    $fields = $elements[$element]['fields'];
                }
                else
                {
                    $fields = array_keys( $modx->getFields($elements[$element]['class_key']));    
                }

                
                if($field != '*' AND in_array($field, $fields) !== true )
                {
                
                    // Error: Unknown field
                    $result['header'] = $modx->lexicon('uberbar_error_unknown_field_header');
                    
                    // You are searching inside an unknown field: <strong>{field}</strong>. Please remove it from your search and try something else.
                    $s = array('{field}');
                    $r = array($field);
                    
                    $result['msg'] = str_replace($s , $r , $modx->lexicon('uberbar_error_unknown_field_msg') );
                    $result['options'] = '';
                    
                    return $result;
                }
            }
            return true;
        }


    /* : */
        if($count == 2){
            $validate = validate_Uberbar($elements,$query);
            
            if($validate === true){
                // Return list of available resource types

                
                $result['header'] = $this->modx->lexicon('uberbar_pick_element_header');
                
                $f = array('{uberbar_mode}');
                $r = array($uberbar_mode);                
                
                $result['msg'] = str_replace($f, $r , $this->modx->lexicon('uberbar_pick_element_msg'));
                r:
                $options = '';
                foreach($elements as $key => $val){
                    /* Only show elements you have permission to see. */
                    if($val['permission'] === true) {
                        $activeClass = ($key == $query[1]) ? 'active ' : '';
                        $options .= '<li><a data-value="'.$key.'" class="'.$activeClass.$btn.'">'.$val['label'].'</a></li>';
                    }
                }           
                $result['options'] = $options;
                
                $this->results[] = $result;
            }
            else{
                $this->results[] = $validate;
            }            
        }
        
    /* :r: */
        if($count == 3){
            $validate = validate_Uberbar($elements,$query);
            
            if($validate === true){
                // Return list of available fields that can be searched
    
                $result['header'] = $this->modx->lexicon('uberbar_pick_fieldname_header');
                
                $f = array('{uberbar_mode}');
                $r = array($uberbar_mode);
                
                $result['msg'] = str_replace($f , $r , $this->modx->lexicon('uberbar_pick_fieldname_msg') );
                
                $options = '';
                $options .= '<li><a data-value="*" class="'.$btn.'">All (*)</a></li>';
                
                if($uberbar_mode == 'simple')
                {
                    $fields = $elements[$element]['fields'];
                }
                else
                {
                    $fields = array_keys( $this->modx->getFields($elements[$element]['class_key']));
                }
                
                foreach($fields as $key => $value){
                    $activeClass = $value == $query[2] ? 'active ' : '';
                    $options .= '<li><a data-value="'.$value.'" class="'.$activeClass.$btn.'">'.$value.'</a></li>';
                }                
                $result['options'] = $options;
                
                $this->results[] = $result;
            }
            else{
                $this->results[] = $validate;
            }
        }
        
        
    /* :r:pagetitle: */
        if($count == 4){
            $validate = validate_Uberbar($elements,$query);
            
            if($validate === true)
            {
                $result['header'] = $this->modx->lexicon('uberbar_enter_searchstring_header');
                $result['msg'] =    $this->modx->lexicon('uberbar_enter_searchstring_msg');
                $result['options'] = '';
                
                $this->results[] = $result;
            }
            else{
                $this->results[] = $validate;
            }            
        }
             
    /* :r:pagetitle:*test*: */
        if($count == 5){
            $validate = validate_Uberbar($elements,$query);

            if($validate === true){
                // Process search query, paginate on demand
                if(!empty($elements[$element]['class_key'])){
                    $this->queryElement($query,$elements,$uberbar_mode);
                }
                elseif($element == 'modx' && $field == 'config'){
                    // 2do
                }
                else{
                    return;
                }
                
            }
            else{
                $this->results[] = $validate;
            }            
        }        

        if($count > 5){
            if($validate === true) {
                $validate = validate_Uberbar($elements,$query,$uberbar_mode);
            }
            else{
                $this->results[] = $validate;
            }            
        }
        
        
    }
    
    public function queryElement($query,$elements,$uberbar_mode){
        
        $startTime = microtime(true);
        
        $maxResults = $this->maxResults;
        
        $btn = "x-btn x-btn-small x-btn-icon-small-left primary-button x-btn-noicon";
        
        $element = $query[1];
        $field   = $query[2];
        $search  = $query[3];
        
        $class_key = $elements[$element]['class_key'];
        $fields = $elements[$element]['fields'];
        
        /*
        if($element == 'modx' AND $field == 'config'){
            // 2do lookup system settings
        }
        else {
            
        }
        */
        
        // Prepare query
        $c = $this->modx->newQuery($class_key);
        
        // Some extra adjustments to search for users
        if($class_key == 'modUser'){
            if($field != 'username'){
                $field = 'Profile.'.$field;
            }                
            $c->select(array(
                $this->modx->getSelectColumns('modUser','modUser'),
                $this->modx->getSelectColumns('modUserProfile', 'Profile', ''),
            ));
            $c->leftJoin('modUserProfile', 'Profile');                    
        }
        elseif($class_key == 'modPlugin'){
            
            // 2do find plugins by event
            // :p:event:onDocFormChange
        }

        
        // Replace wildcards (*) with % if they are not preceeded by [ (2do: or `)
        $search = preg_replace( '/(?<!\[)\*/', '%', $search);
        
        //$search = str_replace('*','%',$search);
        

        
        $where = array();
        
        /* Search inside a field */
        if(!empty($field) AND $field != '*'){
            $like = ($search != $query[3]) ? ':LIKE' : ':=';

            $where = array( $field.$like => $search );
            $c->where($where);
        }
        /* Search inside all fields */
        else {
            // 2do I don't get why := for the else clause doesn't work...
            $like = ($search != $query[3]) ? ':LIKE' : ':LIKE';            

            foreach($fields as $fieldname){
                $n++;
                if($class_key == 'modUser' AND $fieldname != 'username'){
                    if($n == 1)
                    {
                        $key = 'Profile.'.$fieldname.$like;                     
                    }
                    else
                    {
                        $key = 'OR:.Profile'.$fieldname.$like;
                    }
                }
                else {
                    if($n == 1)
                    {
                        $key = $fieldname.$like;
                        
                    }
                    else
                    {
                        $key = 'OR:'.$fieldname.$like;
                    }
                }
                $where[$key] = $search;
            }
            $c->where($where);
        }            
        
        if($debug === true)
        {
            $debug['q'] = '<br/>Full query: <pre style="color: #fff">' . print_r($where,true) . '</pre>';
        }
        
        $totalResults = count($this->modx->getCollection($class_key, $c));
        
        $offset  = is_numeric($query[4]) && $query[4] > 0 ? ( $query[4] - 1 ) * $maxResults : 0;
        $start = ($totalResults) ? $offset + 1 : 0;
        
        if($offset == 0){
            $end = $maxResults > $totalResults ? $totalResults: $maxResults;    
        }
        else {
            $end = ($offset * $query[4] < $totalResults) ? $offset * $query[4] : $totalResults;
        }
        
            
        $pages = ceil($totalResults / $maxResults) ;
        
        switch(count($query)){
            case 4:
                $header = $this->modx->lexicon('uberbar_enter_searchstring_header');
                break;
            default:
                if($totalResults > $maxResults)
                {
                    $activePage = !empty($query[4]) ? $query[4] : '1';
                    for($page = 1; $page <= $pages; $page++){
                        $activeClass = ($page == $activePage ) ? 'active ' : '';
                        $options .= '<li><a data-value="' . $page . '" class="' .$activeClass . $btn . '">' . $page . '</a></li>';
                    }
                }                
                $header = $this->modx->lexicon('uberbar_searchresults_header');
                break;
        }
        
        $exec = number_format(microtime(true) - $startTime , 3);
        
        if($totalResults > 0)
        {
            //'Your search returned <strong>' . $totalResults . ' results </strong> in ' . $exec . ' seconds.';
            
            $s = array('{totalResults}','{exec}');
            $r = array($totalResults,$exec);
            $msg = str_replace( $s , $r , $this->modx->lexicon('uberbar_searchresults_yes' ) );
        }
        else
        {
            //'Your search for <strong>' . . '</strong> returned <strong>0</strong> results. Try again.';
            $your_query = implode(':',$query);
            $s = array('{your_query}');
            $r = array($your_query);
            $msg  = str_replace( $s , $r , $this->modx->lexicon('uberbar_searchresults_no') );
        }
        
        $msg .= $debug['q'];
        
        $c->limit($this->maxResults,$offset);
        
        $collection = $this->modx->getCollection($class_key, $c);
        
        
        
        if(1==1){
            
            $this->results[] = array(
                'uberbar_header'  => $uberbar_header
               
               ,'msg'           => $msg
               ,'options'       => $options
               ,'header'        => $header

               ,'id'            => ''
               ,'name'          => ''
               ,'description'   => ''
               ,'content'       => ''
               ,'icon'          => ''
               
               ,'_action'       => ''
               ,'type'          => ''
               ,'current'       => ''
                

            );
            
            foreach ($collection as $record) {
                
                if($element == 'ss'){
                    $namespace      = $record->get('namespace');
                    $area           = $record->get('area');

                    $id             = $record->get('key');
                    // How do ysystem settings know which language string is right? They are not identical and the names don't follow a pattern...
                    $name           = $this->modx->lexicon('setting_'.$record->get('key'));
                    $description    = '';
                    $content        = $record->get('value');
                }
                else
                {
                    $id             = $record->get('id');
                    $name           = $record->get($elements[$element]['name']);
                    $description    = $record->get($elements[$element]['description']);
                    $content        = $record->get($field);
                }
                
                $icon           = $elements[$element]['icon'];
                $action         = $elements[$element]['action'];
                $type           = $elements[$element]['type'];

                
                if(in_array($field,array('plugincode','snippet','content','introtext'))){

                
                    // find line where $search can be found
                
                        
                    $lines = explode("\n",$content);
                    $content = '';
                    $search = str_replace('%','',$search);

                    $i = 0;
                    foreach($lines as $linenumber => $line){
                        $i++;
                        if(is_numeric(stripos($line,$search))){
                            $r++;
                            
                            $line = htmlspecialchars($line);
                            
                            $linenumber = str_pad($linenumber+1, 3, '0', STR_PAD_LEFT);
                            
                            $line = str_replace($search,'<strong>'.$search.'</strong>',$line);
                            
                            $content .= "<pre class='line'><span class='number'>$linenumber</span><code>$line</code></pre>";
                        }
                        else{
                        }
                    }
                }
                else {
                    $content    = str_replace($search,"<strong>$search</strong>",$content);
                }
                
                $this->results[] = array(
                     'uberbar_mode' => ''
                    ,'msg'          => ''
                    ,'options'      => ''
                    ,'header'       => ''
                    
                    ,'id'           => $id
                    ,'name'         => $name
                    ,'description'  => $description
                    ,'content'      => $content                            
                    ,'icon'         => $icon
                    
                    ,'_action'      => $action.'&id=' . $id
                    ,'type'         => $type

                    ,'current'      => $start++

                );
            }                
        }
        else{
        }
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
    }

    /*
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
