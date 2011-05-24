<?php
/**
 * Represents a template variable element.
 *
 * @todo Refactor this to allow user-defined and configured input and output
 * widgets.
 * @package modx
 */
class modTemplateVar extends modElement {
    /**
     * @var array Supported bindings for MODX
     * @access public
     */
    public $bindings= array (
        'FILE',
        'CHUNK',
        'DOCUMENT',
        'RESOURCE',
        'SELECT',
        'EVAL',
        'INHERIT',
        'DIRECTORY'
    );
    /**
     * Creates a modTemplateVar instance, and sets the token of the class to *
     *
     * {@inheritdoc}
     */
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->setToken('*');
    }

    /**
     * Overrides modElement::save to add custom error logging and fire
     * modX-specific events.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag = null) {
        $isNew = $this->isNew();
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnTemplateVarBeforeSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'templateVar' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        }
        $saved = parent :: save($cacheFlag);

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnTemplateVarSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'templateVar' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        } else if (!$saved && !empty($this->xpdo->lexicon)) {
            $msg = $isNew ? $this->xpdo->lexicon('tv_err_create') : $this->xpdo->lexicon('tv_err_save');
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$msg.$this->toArray());
        }
        return $saved;
    }

    /**
     * Overrides modElement::remove to add custom error logging and fire
     * modX-specific events.
     *
     * {@inheritdoc}
     */
    public function remove(array $ancestors= array ()) {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnTemplateVarBeforeRemove',array(
                'templateVar' => &$this,
                'cacheFlag' => true,
            ));
        }

        $removed = parent :: remove($ancestors);

        if ($removed && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnTemplateVarRemove',array(
                'templateVar' => &$this,
                'cacheFlag' => true,
            ));
        } else if (!$removed && !empty($this->xpdo->lexicon)) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$this->xpdo->lexicon('tv_err_remove').$this->toArray());
        }

        return $removed;
    }

    /**
     * Process the template variable and return the output.
     *
     * {@inheritdoc}
     */
    public function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if (!$this->_processed) {
            $this->_content= $this->renderOutput($this->xpdo->resourceIdentifier);

            /* copy the content source to the output buffer */
            $this->_output= $this->_content;

            if (is_string($this->_output) && !empty ($this->_output)) {
                /* turn the processed properties into placeholders */
                $scope = $this->xpdo->toPlaceholders($this->_properties, '', '.', true);

                /* collect element tags in the content and process them */
                $maxIterations= intval($this->xpdo->getOption('parser_max_iterations',null,10));
                $this->xpdo->parser->processElementTags($this->_tag, $this->_output, false, false, '[[', ']]', array(), $maxIterations);

                /* remove the placeholders set from the properties of this element and restore global values */
                if (isset($scope['keys'])) $this->xpdo->unsetPlaceholders($scope['keys']);
                if (isset($scope['restore'])) $this->xpdo->toPlaceholders($scope['restore']);
            }

            /* apply output filtering */
            $this->filterOutput();

            /* cache the content */
            $this->cache();

            $this->_processed= true;
        }
        /* finally, return the processed element content */
        return $this->_output;
    }

    /**
     * Get the source content of this template variable.
     *
     * {@inheritdoc}
     */
    public function getContent(array $options = array()) {
        if (!is_string($this->_content) || $this->_content === '') {
            if (isset($options['content'])) {
                $this->_content = $options['content'];
            } else {
                $this->_content = $this->get('default_text');
            }
        }
        return $this->_content;
    }

    /**
     * Set the source content of this template variable.
     *
     * {@inheritdoc}
     */
    public function setContent($content, array $options = array()) {
        return $this->set('default_text', $content);
    }

    /**
     * Get the value of a template variable for a resource.
     *
     * @access public
     * @param integer $resourceId The id of the resource; 0 defaults to the default_text field for the tv.
     * @return mixed The raw value of the template variable in context of the
     * specified resource or the default_text for the tv.
     */
    public function getValue($resourceId= 0) {
        $value= null;
        $resourceId = intval($resourceId);
        if ($resourceId) {
            if (is_object($this->xpdo->resource) && $resourceId === (integer) $this->xpdo->resourceIdentifier && is_array($this->xpdo->resource->get($this->get('name')))) {
                $valueArray= $this->xpdo->resource->get($this->get('name'));
                $value= $valueArray[1];
            } elseif ($resourceId === (integer) $this->get('resourceId') && array_key_exists('value', $this->_fields)) {
                $value= $this->get('value');
            } else {
                $resource = $this->xpdo->getObject('modTemplateVarResource',array(
                    'tmplvarid' => $this->get('id'),
                    'contentid' => $resourceId,
                ),true);
                if ($resource && $resource instanceof modTemplateVarResource) {
                    $value= $resource->get('value');
                }
            }
        }
        if ($value === null) {
            $value= $this->get('default_text');
        }
        return $value;
    }

    /**
     * Set the value of a template variable for a resource.
     *
     * @access public
     * @param integer $resourceId The id of the resource; 0 defaults to the
     * current resource.
     * @param mixed $value The value to give the template variable for the
     * specified document.
     */
    public function setValue($resourceId= 0, $value= null) {
        $oldValue= '';
        if (intval($resourceId)) {
            $templateVarResource = $this->xpdo->getObject('modTemplateVarResource',array(
                'tmplvarid' => $this->get('id'),
                'contentid' => $resourceId,
            ),true);

            if (!$templateVarResource) {
                $templateVarResource= $this->xpdo->newObject('modTemplateVarResource');
            }

            if ($value !== $this->get('default_text')) {
                if (!$templateVarResource->isNew()) {
                    $templateVarResource->set('value', $value);
                } else {
                    $templateVarResource->set('contentid', $resourceId);
                    $templateVarResource->set('value', $value);
                }
                $this->addMany($templateVarResource);
            } elseif (!$templateVarResource->isNew()
                  && ($value === null || $value === $this->get('default_text'))) {
                $templateVarResource->remove();
            }
        }
    }

    /**
     * Returns the processed output of a template variable.
     *
     * @access public
     * @param integer $resourceId The id of the resource; 0 defaults to the
     * current resource.
     * @return mixed The processed output of the template variable.
     */
    public function renderOutput($resourceId= 0) {
        $value= $this->getValue($resourceId);

        /* process any TV commands in value */
        $value= $this->processBindings($value, $resourceId);

        $params= array ();
        /**
         * Backwards support for display_params
         * @deprecated To be removed in 2.2
         */
        if ($paramstring= $this->get('display_params')) {
            $cp= explode("&", $paramstring);
            foreach ($cp as $p => $v) {
                $ar= explode("=", $v);
                if (is_array($ar) && count($ar) == 2) {
                    $params[$ar[0]]= $this->decodeParamValue($ar[1]);
                }
            }
        }
        /* get output_properties for rendering properties */
        $outputProperties = $this->get('output_properties');
        if (!empty($outputProperties) && is_array($outputProperties)) {
            $params = array_merge($params,$outputProperties);
        }

        /* for base_url in image/file tvs */
        if (!empty($value) && in_array($this->get('type'),array('image','file'))) {
            $ips = $this->get('input_properties');
            $fmu = $this->xpdo->getOption('filemanager_url',null,'');
            $absValCheck = substr($value,0,1) == '/' || substr($value,0,7) == 'http://' || substr($value,0,8) == 'https://';
            if (empty($ips['baseUrlPrependCheckSlash']) || !($absValCheck)) {
                if (!empty($ips['baseUrl'])) {
                    $value = $ips['baseUrl'].$value;
                } else if (!empty($fmu)) {
                    $value = $fmu.$value;
                }
            }
        }

        /* find the render */
        $outputRenderPaths = $this->getRenderDirectories('OnTVOutputRenderList','output');
        return $this->getRender($params,$value,$outputRenderPaths,'output',$resourceId,$this->get('display'));
    }

    /**
     * Renders input forms for the template variable.
     *
     * @access public
     * @param integer $resourceId The id of the resource; 0 defaults to the
     * current resource.
     * @param string $style Extra style parameters. (deprecated)
     * @return mixed The rendered input for the template variable.
     */
    public function renderInput($resourceId= 0, $style= '') {
        if (!isset($this->smarty)) {
            $this->xpdo->getService('smarty', 'smarty.modSmarty', '', array(
                'template_dir' => $this->xpdo->getOption('manager_path') . 'templates/' . $this->xpdo->getOption('manager_theme',null,'default') . '/',
            ));
        }
        $this->xpdo->smarty->assign('style',$style);
        $value = $this->getValue($resourceId);

        /* process only @INHERIT bindings in value, since we're inputting */
        $bdata = $this->getBindingDataFromValue($value);
        if ($bdata['cmd'] == 'INHERIT') {
            $value = $this->processInheritBinding($bdata['param'], $resourceId);
        }

        /* if any FC tvDefault rules, set here */
        if ($this->xpdo->request && $this->xpdo->user instanceof modUser) {
            if (!empty($resourceId)) {
                $resource = $this->xpdo->getObject('modResource',$resourceId);
            }
            $userGroups = $this->xpdo->user->getUserGroups();
            $c = $this->xpdo->newQuery('modActionDom');
            $c->innerJoin('modFormCustomizationSet','FCSet');
            $c->innerJoin('modFormCustomizationProfile','Profile','FCSet.profile = Profile.id');
            $c->leftJoin('modFormCustomizationProfileUserGroup','ProfileUserGroup','Profile.id = ProfileUserGroup.profile');
            $c->leftJoin('modFormCustomizationProfile','UGProfile','UGProfile.id = ProfileUserGroup.profile');
            $ruleFieldName = $this->xpdo->escape('rule');
            $c->where(array(
                array(
                    "(modActionDom.{$ruleFieldName} = 'tvDefault'
                   OR modActionDom.{$ruleFieldName} = 'tvVisible'
                   OR modActionDom.{$ruleFieldName} = 'tvTitle')"
                ),
                "'tv{$this->get('id')}' IN ({$this->xpdo->escape('modActionDom')}.{$this->xpdo->escape('name')})",
                'FCSet.active' => true,
                'Profile.active' => true,
            ));
            $c->where(array(
                array(
                    'ProfileUserGroup.usergroup:IN' => $userGroups,
                    array(
                        'OR:ProfileUserGroup.usergroup:IS' => null,
                        'AND:UGProfile.active:=' => true,
                    ),
                ),
                'OR:ProfileUserGroup.usergroup:=' => null,
            ),xPDOQuery::SQL_AND,null,2);
            if (!empty($this->xpdo->request) && !empty($this->xpdo->request->action)) {
                $c->where(array(
                    'modActionDom.action' => $this->xpdo->request->action,
                ));
            }
            $c->select(array(
                'modActionDom.*',
                'FCSet.constraint_class',
                'FCSet.constraint_field',
                'FCSet.' . $this->xpdo->escape('constraint'),
                'FCSet.template',
            ));
            $c->sortby('FCSet.template','ASC');
            $c->sortby('modActionDom.rank','ASC');
            $domRules = $this->xpdo->getCollection('modActionDom',$c);
            foreach ($domRules as $rule) {
                if (!empty($resourceId)) {
                    $template = $rule->get('template');
                    if (!empty($template)) {
                        if ($resource && $template != $resource->get('template')) continue;
                    }
                }
                switch ($rule->get('rule')) {
                    case 'tvVisible':
                        if ($rule->get('value') == 0) {
                            $this->set('type','hidden');
                        }
                        break;
                    case 'tvDefault':
                        $v = $rule->get('value');
                        if (empty($resourceId)) {
                            $value = $v;
                            $this->set('value',$v);
                        }
                        $this->set('default_text',$v);
                        break;
                    case 'tvTitle':
                        $v = $rule->get('value');
                        $this->set('caption',$v);
                        break;
                }
            }
            unset($domRules,$rule,$userGroups,$v,$c);
        }
        /* properly set value back if any FC rules, resource values, or bindings have adjusted it */
        $this->set('value',$value);
        $this->set('processedValue',$value);
        $this->set('default_text',$this->processBindings($this->get('default_text'),$resourceId));

        /* strip tags from description */
        $this->set('description',strip_tags($this->get('description')));

        $this->xpdo->smarty->assign('tv',$this);
        $this->xpdo->smarty->assign('ctx',isset($_REQUEST['ctx']) ? $_REQUEST['ctx'] : 'web');

        $params= array ();
        if ($paramstring= $this->get('display_params')) {
            $cp= explode("&", $paramstring);
            foreach ($cp as $p => $v) {
                $v= trim($v);
                $ar= explode("=", $v);
                if (is_array($ar) && count($ar) == 2) {
                    $params[$ar[0]]= $this->decodeParamValue($ar[1]);
                }
            }
        }

        $params = $this->get('input_properties');
        /* default required status to no */
        if (!isset($params['allowBlank'])) $params['allowBlank'] = 1;
        $this->xpdo->smarty->assign('params',$params);
        $this->xpdo->lexicon->load('tv_widget');

        /* find the correct renderer for the TV, if not one, render a textbox */
        $inputRenderPaths = $this->getRenderDirectories('OnTVInputRenderList','input');
        return $this->getRender($params,$value,$inputRenderPaths,'input',$resourceId,$this->get('type'));
    }

    /**
     * Gets the correct render given paths and type of render
     *
     * @param array $params The parameters to pass to the render
     * @param mixed $value The value of the TV
     * @param array $paths An array of paths to search
     * @param string $method The type of Render (input/output/properties)
     * @param integer $resourceId The ID of the current Resource
     * @param string $type The type of render to display
     * @return string
     */
    public function getRender($params,$value,array $paths,$method,$resourceId = 0,$type = 'text') {
        /* backwards compat stuff */
        $name= $this->get('name');
        $id= "tv$name";
        $format= $this->get('display');
        $tvtype= $this->get('type');
        /* end backwards compat */
        
        $modx =& $this->xpdo;
        if (empty($modx->resource)) {
            if (!empty($resourceId)) {
                $modx->resource = $modx->getObject('modResource',$resourceId);
            }
            if (empty($modx->resource) || empty($resourceId)) {
                $modx->resource = $modx->newObject('modResource');
                $modx->resource->set('id',0);
            }
        }

        $output = '';
        foreach ($paths as $path) {
            $renderFile = $path.$type.'.php';
            if (file_exists($renderFile)) {
                $output = include $renderFile;
                break;
            }
        }
        if (empty($output)) {
            $p = $this->xpdo->getOption('processors_path').'element/tv/renders/'.$this->xpdo->context->get('key').'/'.$method.'/';
            if (file_exists($p.'text.php')) {
                $output = include $p.'text.php';
            } else {
                $output = include $this->xpdo->getOption('processors_path').'element/tv/renders/'.($method == 'input' ? 'mgr' : 'web').'/'.$method.'/text.php';
            }
        }
        return $output;
    }

    /**
     * Finds the correct directories for renders
     *
     * @param string $event The plugin event to fire
     * @param string $subdir The subdir to search
     * @return array The found render directories
     */
    public function getRenderDirectories($event,$subdir) {
        $renderPath = $this->xpdo->getOption('processors_path').'element/tv/renders/'.$this->xpdo->context->get('key').'/'.$subdir.'/';
        $renderDirectories = array(
            $renderPath,
            $this->xpdo->getOption('processors_path').'element/tv/renders/'.($subdir == 'input' ? 'mgr' : 'web').'/'.$subdir.'/',
        );
        $pluginResult = $this->xpdo->invokeEvent($event,array(
            'context' => $this->xpdo->context->get('key'),
        ));
        if (!is_array($pluginResult) && !empty($pluginResult)) { $pluginResult = array($pluginResult); }
        if (!empty($pluginResult)) {
            foreach ($pluginResult as $result) {
                if (empty($result)) continue;
                $renderDirectories[] = $result;
            }
        }

        /* search directories */
        $types = array();
        $renderPaths = array();
        foreach ($renderDirectories as $renderDirectory) {
            if (empty($renderDirectory) || !is_dir($renderDirectory)) continue;
            try {
                $dirIterator = new DirectoryIterator($renderDirectory);
                foreach ($dirIterator as $file) {
                    if (!$file->isReadable() || !$file->isFile()) continue;
                    $renderPaths[] = dirname($file->getPathname()).'/';
                }
            } catch (UnexpectedValueException $e) {}
        }
        $renderPaths = array_unique($renderPaths);
        return $renderPaths;
    }

    /**
     * Decodes special function-based chars from a parameter value.
     *
     * @access public
     * @param string $s The string to decode.
     * @return string The decoded string.
     */
    public function decodeParamValue($s) {
        $s= str_replace(array("%3D",'&#61;'), '=', $s);
        $s= str_replace("%26", '&', $s);
        return $s;
    }

    /**
     * Returns an array of display params for this TV
     *
     * @return array The processed settings
     */
    public function getDisplayParams() {
        $settings = array();
        $params = $this->get('display_params');
        $ps = explode('&',$params);
        foreach ($ps as $p) {
            $param = explode('=',$p);
            if ($p[0] != '') {
                $v = $param[1];
                if ($v == 'true') $v = 1;
                if ($v == 'false') $v = 0;
                $settings[$param[0]] = $v;
            }
        }
        return $settings;
    }

    /**
     * Returns an string if a delimiter is present. Returns array if is a recordset is present.
     *
     * @access public
     * @param mixed $src Source object, either a recordset, PDOStatement, array or string.
     * @param string $delim Delimiter for string parsing.
     * @param string $type Type to return, either 'string' or 'array'.
     *
     * @return string|array If delimiter present, returns string, otherwise array.
     */
    public function parseInput($src, $delim= "||", $type= "string") {
        if (is_resource($src)) {
            /* must be a recordset */
            $rows= array ();
            while ($cols= mysql_fetch_row($src))
                $rows[]= ($type == "array") ? $cols : implode(" ", $cols);
            return ($type == "array") ? $rows : implode($delim, $rows);
        } elseif (is_object($src)) {
            $rs= $src->fetchAll(PDO::FETCH_ASSOC);
            if ($type != "array") {
                foreach ($rs as $row) {
                    $rows[]= implode(" ", $row);
                }
            } else {
                $rows= $rs;
            }
            return ($type == "array" ? $rows : implode($delim, $rows));
        } elseif (is_array($src) && $type == "array") {
            return ($type == "array" ? $src : implode($delim, $src));
        } else {
            /* must be a text */
            if ($type == "array")
                return explode($delim, $src);
            else
                return $src;
        }
    }

    /**
     * Parses input options sent through postback.
     *
     * @access public
     * @param mixed $v The options to parse, either a recordset, PDOStatement, array or string.
     * @return mixed The parsed options.
     */
    public function parseInputOptions($v) {
        $a = array();
        if(is_array($v)) return $v;
        else if(is_resource($v)) {
            while ($cols = mysql_fetch_row($v)) $a[] = $cols;
        } else if (is_object($v)) {
            $a = $v->fetchAll(PDO::FETCH_ASSOC);
        }
        else $a = explode("||", $v);
        return $a;
    }

    /**
     * Parses the binding data from a value
     *
     * @param mixed $value The value to parse
     * @return array An array of cmd and param for the binding
     */
    public function getBindingDataFromValue($value) {
        $nvalue = trim($value);
        $cmd = false;
        $param = '';
        if (substr($nvalue,0,1) == '@') {
            list($cmd,$param) = $this->parseBinding($nvalue);
            $cmd = trim($cmd);
        }
        return array('cmd' => $cmd,'param' => $param);
    }

    /**
     * Process bindings assigned to a template variable.
     *
     * @access public
     * @param string $value The value specified from the binding.
     * @param integer $resourceId The resource in which the TV is assigned.
     * @param boolean $preProcess Whether or not to process certain bindings.
     * @return string The processed value.
     */
    public function processBindings($value= '', $resourceId= 0, $preProcess = true) {
        $bdata = $this->getBindingDataFromValue($value);
        if (empty($bdata['cmd'])) return $value;

        $modx =& $this->xpdo;
        if (empty($modx->resource)) {
            if (!empty($resourceId)) {
                $modx->resource = $modx->getObject('modResource',$resourceId);
            }
            if (empty($modx->resource) || empty($resourceId)) {
                $modx->resource = $modx->newObject('modResource');
                $modx->resource->set('id',0);
            }
        }
        $cmd = $bdata['cmd'];
        $param = !empty($bdata['param']) ? $bdata['param'] : null;
        switch ($cmd) {
            case 'FILE':
                if ($preProcess) {
                    $output = $this->processFileBinding($param);
                }
                break;

            case 'CHUNK': /* retrieve a chunk and process it's content */
                if ($preProcess) {
                    $output = $this->xpdo->getChunk($param);
                }
                break;

            case 'RESOURCE':
            case 'DOCUMENT': /* retrieve a document and process it's content */
                if ($preProcess) {
                    $query = $this->xpdo->newQuery('modResource', array(
                        'id' => (integer) $param,
                        'deleted' => false
                    ));
                    $query->select('content');
                    if ($query->prepare() && $query->stmt->execute()) {
                        $output = $query->stmt->fetch(PDO::FETCH_COLUMN);
                    } else {
                        $output = 'Unable to locate resource '.$param;
                    }
                }
                break;

            case 'SELECT': /* selects a record from the cms database */
                if ($preProcess) {
                    $dbtags = array();
                    if ($modx->resource && $modx->resource instanceof modResource) {
                        $dbtags = $modx->resource->toArray();
                    }
                    $dbtags['DBASE'] = $this->xpdo->getOption('dbname');
                    $dbtags['PREFIX'] = $this->xpdo->getOption('table_prefix');
                    foreach($dbtags as $key => $pValue) {
                        $param = str_replace('[[+'.$key.']]', $pValue, $param);
                    }
                    $stmt = $this->xpdo->query('SELECT '.$param);
                    if ($stmt && $stmt instanceof PDOStatement) {
                        $data = '';
                        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                            $col = '';
                            if (isset($row[1])) {
                                $col = $row[0].'=='.$row[1];
                            } else {
                                $col = $row[0];
                            }
                            $data .= (!empty($data) ? '||' : '').$col;
                        }
                        $stmt->closeCursor();
                    }
                    $output = $data;
                }
                break;

            case 'EVAL':        /* evaluates text as php codes return the results */
                if ($preProcess) {
                    $output = eval($param);
                }
                break;

            case 'INHERIT':
                if ($preProcess) {
                    $output = $this->processInheritBinding($param,$resourceId);
                } else {
                    $output = $value;
                }
                break;

            case 'DIRECTORY':
                $path = $this->xpdo->getOption('base_path').$param;
                if (substr($path,-1,1) != '/') { $path .= '/'; }
                if (!is_dir($path)) { break; }

                $files = array();
                $invalid = array('.','..','.svn','.git','.DS_Store');
                foreach (new DirectoryIterator($path) as $file) {
                    if (!$file->isReadable()) continue;
                    $basename = $file->getFilename();
                    if(!in_array($basename,$invalid)) {
                        $files[] = "{$basename}=={$param}/{$basename}";
                    }
                }
                asort($files);
                $output = implode('||',$files);
                break;

            default:
                $output = $value;
                break;

        }
        /* support for nested bindings */
        return is_string($output) && ($output != $value) ? $this->processBindings($output) : $output;
    }

    /**
     * Parses bindings to an appropriate format.
     *
     * @access public
     * @param string $binding_string The binding to parse.
     * @return array The parsed binding, now in array format.
     */
    public function parseBinding($binding_string) {
        $match= array ();
        $binding_string= trim($binding_string);
        $regexp= '/@(' . implode('|', $this->bindings) . ')\s*(.*)/is'; /* Split binding on whitespace */
        if (preg_match($regexp, $binding_string, $match)) {
            /* We can't return the match array directly because the first element is the whole string */
            $binding_array= array (
                strtoupper($match[1]),
                trim($match[2])
            ); /* Make command uppercase */
            return $binding_array;
        }
    }

    /**
     * Parse inherit binding
     *
     * @param string $default The value to default if there is no inherited value
     * @param int $resourceId The current Resource, if any
     * @return string The inherited value
     */
    public function processInheritBinding($default = '',$resourceId = null) {
        $output = $default; /* Default to param value if no content from parents */
        $resource = null;
        $resourceColumns = $this->xpdo->getSelectColumns('modResource', '', '', array('id', 'parent'));
        $resourceQuery = new xPDOCriteria($this->xpdo, "SELECT {$resourceColumns} FROM {$this->xpdo->getTableName('modResource')} WHERE id = ?");
        if (!empty($resourceId) && (!($this->xpdo->resource instanceof modResource) || $this->xpdo->resource->get('id') != $resourceId)) {
            if ($resourceQuery->stmt && $resourceQuery->stmt->execute(array($resourceId))) {
                $result = $resourceQuery->stmt->fetchAll(PDO::FETCH_ASSOC);
                $resource = reset($result);
            }
        } else if ($this->xpdo->resource instanceof modResource) {
            $resource = $this->xpdo->resource->get(array('id', 'parent'));
        }
        if (!empty($resource)) {
            $currentResource = $resource;
            while ($currentResource['parent'] != 0) {
                if ($resourceQuery->stmt && $resourceQuery->stmt->execute(array($currentResource['parent']))) {
                    $result = $resourceQuery->stmt->fetchAll(PDO::FETCH_ASSOC);
                    $currentResource = reset($result);
                } else {
                    break;
                }
                if (!empty($currentResource)) {
                    $tv = $this->getValue($currentResource['id']);
                    if (($tv === '0' || !empty($tv)) && substr($tv,0,1) != '@') {
                        $output = $tv;
                        break;
                    }
                } else {
                    break;
                }
            }
        }
        return $output;
    }

    /**
     * Special parsing for file bindings.
     *
     * @access public
     * @param string $file The absolute location of the file in the binding.
     * @return string The file buffer from the read file.
     */
    public function processFileBinding($file) {
        if (file_exists($file) && @ $handle= fopen($file,'r')) {
            $buffer= "";
            while (!feof($handle)) {
                $buffer .= fgets($handle, 4096);
            }
            fclose($handle);
        } else {
            $buffer= " Could not retrieve document '$file'.";
        }
        $displayParams = $this->getDisplayParams();
        if (!empty($displayParams['delimiter'])) {
            $buffer = str_replace("\n",'||',$buffer);
        }
        return $buffer;
    }

    /**
     * Loads the access control policies applicable to this template variable.
     *
     * {@inheritdoc}
     */
    public function findPolicy($context = '') {
        $policy = array();
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if ($context === $this->xpdo->context->get('key')) {
            $catEnabled = (boolean) $this->xpdo->getOption('access_category_enabled', null, true);
            $rgEnabled = (boolean) $this->xpdo->getOption('access_resource_group_enabled', null, true);
        } elseif ($this->xpdo->getContext($context)) {
            $catEnabled = (boolean) $this->xpdo->contexts[$context]->getOption('access_category_enabled', true);
            $rgEnabled = (boolean) $this->xpdo->contexts[$context]->getOption('access_resource_group_enabled', true);
        }
        $enabled = ($catEnabled || $rgEnabled);
        if ($enabled) {
            if (empty($this->_policies) || !isset($this->_policies[$context])) {
                if ($rgEnabled) {
                    $accessTable = $this->xpdo->getTableName('modAccessResourceGroup');
                    $policyTable = $this->xpdo->getTableName('modAccessPolicy');
                    $resourceGroupTable = $this->xpdo->getTableName('modTemplateVarResourceGroup');
                    $sql = "SELECT Acl.target, Acl.principal, Acl.authority, Acl.policy, Policy.data FROM {$accessTable} Acl " .
                            "LEFT JOIN {$policyTable} Policy ON Policy.id = Acl.policy " .
                            "JOIN {$resourceGroupTable} ResourceGroup ON Acl.principal_class = 'modUserGroup' " .
                            "AND (Acl.context_key = :context OR Acl.context_key IS NULL OR Acl.context_key = '') " .
                            "AND ResourceGroup.tmplvarid = :element " .
                            "AND ResourceGroup.documentgroup = acl.target " .
                            "GROUP BY Acl.target, Acl.principal, Acl.authority, Acl.policy";
                    $bindings = array(
                        ':element' => $this->get('id'),
                        ':context' => $context
                    );
                    $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
                    if ($query->stmt && $query->stmt->execute()) {
                        while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                            $policy['modAccessResourceGroup'][$row['target']][] = array(
                                'principal' => $row['principal'],
                                'authority' => $row['authority'],
                                'policy' => $row['data'] ? $this->xpdo->fromJSON($row['data'], true) : array(),
                            );
                        }
                    }
                }
                if ($catEnabled) {
                    $accessTable = $this->xpdo->getTableName('modAccessCategory');
                    $categoryClosureTable = $this->xpdo->getTableName('modCategoryClosure');
                    $sql = "SELECT Acl.target, Acl.principal, Acl.authority, Acl.policy, Policy.data FROM {$accessTable} Acl " .
                            "LEFT JOIN {$policyTable} Policy ON Policy.id = Acl.policy " .
                            "JOIN {$categoryClosureTable} CategoryClosure ON CategoryClosure.descendant = :category " .
                            "AND Acl.principal_class = 'modUserGroup' " .
                            "AND CategoryClosure.ancestor = Acl.target " .
                            "AND (Acl.context_key = :context OR Acl.context_key IS NULL OR Acl.context_key = '') " .
                            "GROUP BY target, principal, authority, policy " .
                            "ORDER BY CategoryClosure.depth DESC, authority ASC";
                    $bindings = array(
                        ':category' => $this->get('category'),
                        ':context' => $context,
                    );
                    $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
                    if ($query->stmt && $query->stmt->execute()) {
                        while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                            $policy['modAccessCategory'][$row['target']][] = array(
                                'principal' => $row['principal'],
                                'authority' => $row['authority'],
                                'policy' => $row['data'] ? $this->xpdo->fromJSON($row['data'], true) : array(),
                            );
                        }
                    }
                }
                $this->_policies[$context] = $policy;
            } else {
                $policy = $this->_policies[$context];
            }
        }
        return $policy;
    }

    /**
     * Check to see if the TV has access to a Template
     *
     * @param mixed $templatePk Either the ID, name or object of the Template
     * @return boolean Whether or not the TV has access to the specified Template
     */
    public function hasTemplate($templatePk) {
        if (!is_int($templatePk) && !is_object($templatePk)) {
            $template = $this->xpdo->getObject('modTemplate',array('templatename' => $templatePk));
            if (empty($template) || !is_object($template) || !($template instanceof modTemplate)) {
                $this->xpdo->log(modX::LOG_LEVEL_ERROR,'modTemplateVar::hasTemplate - No template: '.$templatePk);
                return false;
            }
        } else {
            $template =& $templatePk;
        }
        $templateVarTemplate = $this->xpdo->getObject('modTemplateVarTemplate',array(
            'tmplvarid' => $this->get('id'),
            'templateid' => is_object($template) ? $template->get('id') : $template,
        ));
        return !empty($templateVarTemplate) && is_object($templateVarTemplate);
    }
}
