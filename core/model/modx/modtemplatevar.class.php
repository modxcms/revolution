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
 * Represents a template variable element.
 *
 * @property string $type The input type of this TV
 * @property string $name The name of this TV, and key by which it will be referenced in tags
 * @property string $caption The caption that will be used to display the name of this TV when on the Resource page
 * @property string $description A user-provided description of this TV
 * @property int $editor_type Deprecated
 * @property int $category The Category for this TV, or 0 if not in one
 * @property boolean $locked Whether or not this TV can only be edited by an Administrator
 * @property string $elements Default values for this TV
 * @property int $rank The rank of the TV when sorted and displayed relative to other TVs in its Category
 * @property string $display The output render type of this TV
 * @property string $default_text The default value of this TV if no other value is set
 * @property string $properties An array of default properties for this TV
 * @property string $input_properties An array of input properties related to the rendering of the input of this TV
 * @property string $output_properties An array of output properties related to the rendering of the output of this TV
 *
 * @todo Refactor this to allow user-defined and configured input and output
 * widgets.
 * @see modTemplateVarResource
 * @see modTemplateVarResourceGroup
 * @see modTemplateVarResourceTemplate
 * @see modTemplate
 * @package modx
 */
class modTemplateVar extends modElement {
    /**
     * Supported bindings for MODX
     * @var array $bindings
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
    /** @var modX $xpdo */
    public $xpdo;

    /**
     * A cache for modTemplateVar::getRenderDirectories()
     * @see getRenderDirectories()
     * @var array $_renderPaths
     */
    private static $_renderPaths = array();

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

        $saved = parent::save($cacheFlag);

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
                $this->xpdo->parser->processElementTags(
                    $this->_tag,
                    $this->_output,
                    $this->xpdo->parser->isProcessingUncacheable(),
                    $this->xpdo->parser->isRemovingUnprocessed(),
                    '[[',
                    ']]',
                    array(),
                    $maxIterations
                );

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
        $this->xpdo->parser->setProcessingElement(false);
        /* finally, return the processed element content */
        return $this->_output;
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
            $this->xpdo->deprecated('2.2.0', 'Use output_properties instead.', 'modTemplateVar renderOutput display_params');
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

        /* run prepareOutput to allow for custom overriding */
        $value = $this->prepareOutput($value, $resourceId);

        /* find the render */
        $outputRenderPaths = $this->getRenderDirectories('OnTVOutputRenderList','output');
        return $this->getRender($params,$value,$outputRenderPaths,'output',$resourceId,$this->get('display'));
    }

    /**
     * Prepare the output in this method to allow processing of this without depending on the actual render of the output
     * @param string $value
     * @param integer $resourceId The id of the resource; 0 defaults to the
     * current resource.
     * @return string
     */
    public function prepareOutput($value, $resourceId= 0) {
        /* Allow custom source types to manipulate the output URL for image/file tvs */
        $mTypes = $this->xpdo->getOption('manipulatable_url_tv_output_types',null,'image,file');
        $mTypes = explode(',',$mTypes);
        if (!empty($value) && in_array($this->get('type'),$mTypes)) {
            $context = !empty($resourceId) ? $this->xpdo->getObject('modResource', $resourceId)->get('context_key') : $this->xpdo->context->get('key');
            $sourceCache = $this->getSourceCache($context);
            if (!empty($sourceCache) && !empty($sourceCache['class_key'])) {
                $coreSourceClasses = $this->xpdo->getOption('core_media_sources',null,'modFileMediaSource,modS3MediaSource');
                $coreSourceClasses = explode(',',$coreSourceClasses);
                $classKey = in_array($sourceCache['class_key'],$coreSourceClasses) ? 'sources.'.$sourceCache['class_key'] : $sourceCache['class_key'];
                if ($this->xpdo->loadClass($classKey)) {
                    /** @var modMediaSource $source */
                    $source = $this->xpdo->newObject($classKey);
                    if ($source) {
                        $source->fromArray($sourceCache,'',true,true);
                        $source->initialize();
                        $isAbsolute = strpos($value,'http://') === 0 || strpos($value,'https://') === 0 || strpos($value,'ftp://') === 0;
                        if (!$isAbsolute) {
                            $value = $source->prepareOutputUrl($value);
                        }
                    }
                }
            }
        }
        return $value;
    }

    /**
     * Renders input forms for the template variable.
     *
     * @access public
     * @param modResource|null $resource The resource; 0 defaults to the current resource.
     * @param mixed $options Array of options ('value', 'style') or deprecated $style string
     * @return mixed The rendered input for the template variable.
     */
    public function renderInput($resource= null, $options = array()) {
        if (is_int($resource)) {
            $resource = $this->xpdo->getObject('modResource',$resource);
        }
        if (empty($resource)) {
            $resource = $this->xpdo->resource;
        } else {
            $this->xpdo->resource = $resource;
        }
        $resourceId = $resource ? $resource->get('id') : 0;

        if (is_string($options) && !empty($options)) {
            // fall back to deprecated $style setting
            $style = $options;
        } else {
            $style = is_array($options) && isset($options['style']) ? strval($options['style']) : '';
            $value = is_array($options) && isset($options['value']) ? strval($options['value']) : '';
        }
        if (!isset($this->xpdo->smarty)) {
            $this->xpdo->getService('smarty', 'smarty.modSmarty', '', array(
                'template_dir' => $this->xpdo->getOption('manager_path') . 'templates/' . $this->xpdo->getOption('manager_theme',null,'default') . '/',
            ));
        }
        $this->xpdo->smarty->assign('style',$style);
        if(!isset($value) || empty($value)) {
            $value = $this->getValue($resourceId);
        }

        /* process only @INHERIT bindings in value, since we're inputting */
        $bindingData = $this->getBindingDataFromValue($value);
        if ($bindingData['cmd'] == 'INHERIT') {
            $value = $this->processInheritBinding($bindingData['param'], $resourceId);
        }

        /* if any FC tvDefault rules, set here */
        $value = $this->checkForFormCustomizationRules($value,$resource);
        /* properly set value back if any FC rules, resource values, or bindings have adjusted it */
        $this->set('value',$value);
        $this->set('processedValue',$value);
        $this->set('default_text',$this->processBindings($this->get('default_text'),$resourceId));

        /* strip tags from description */
        $this->set('description',strip_tags($this->get('description')));

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
        if (empty($type)) $type = 'text';
        if (empty($this->xpdo->resource)) {
            if (!empty($resourceId)) {
                $this->xpdo->resource = $this->xpdo->getObject('modResource',$resourceId);
            }
            if (empty($this->xpdo->resource) || empty($resourceId)) {
                $this->xpdo->resource = $this->xpdo->newObject('modResource');
                $this->xpdo->resource->set('id',0);
            }
        }

        if ($className = $this->checkForRegisteredRenderMethod($type,$method)) {
            /** @var modTemplateVarOutputRender $render */
            $render = new $className($this);
            $output = $render->render($value,$params);
        } else {
            $deprecatedClassName = $method == 'input' ? 'modTemplateVarInputRenderDeprecated' : 'modTemplateVarOutputRenderDeprecated';
            $render = new $deprecatedClassName($this);

            foreach ($paths as $path) {
                $renderFile = $path.$type.'.class.php';
                if (file_exists($renderFile)) {
                    $className = include $renderFile;
                    $this->registerRenderMethod($type,$method,$className);
                    if (class_exists($className)) {
                        /** @var modTemplateVarOutputRender $render */
                        $render = new $className($this);
                    }
                    break;
                }

                /* 2.1< backwards compat */
                $renderFile = $path.$type.'.php';
                if (file_exists($renderFile)) {
                    $this->xpdo->deprecated('2.2.0', '', 'Old style template variable with flat render file ' . $renderFile . ', for TV ' . $this->get('name'));
                    $render = new $deprecatedClassName($this);
                    $params['modx.renderFile'] = $renderFile;
                    break;
                }
            }

            $output = $render->render($value,$params);

            /* if no output, fallback to text */
            if (empty($output)) {
                $p = $this->xpdo->getOption('processors_path').'element/tv/renders/mgr/'.$method.'/';
                $className = $method == 'output' ? 'modTemplateVarOutputRenderText' : 'modTemplateVarInputRenderText';
                if (!class_exists($className) && file_exists($p.'text.class.php')) {
                    $className = include $p.'text.class.php';
                }
                if (class_exists($className)) {
                    $render = new $className($this);
                    $output = $render->render($value,$params);
                }
            }
        }
        return $output;
    }

    /**
     * Check for a registered TV render
     * @param string $type
     * @param string $method
     * @return bool
     */
    public function checkForRegisteredRenderMethod($type,$method) {
        $v = false;
        if (!isset($this->xpdo->tvRenders)) $this->xpdo->tvRenders = array();
        if (!isset($this->xpdo->tvRenders[$method])) {
            $this->xpdo->tvRenders[$method] = array();
        }
        if (!empty($this->xpdo->tvRenders[$method][$type])) {
            $v = $this->xpdo->tvRenders[$method][$type];
        }
        return $v;
    }

    /**
     * Register a render method to the array cache to prevent double loading of the class
     * @param string $type
     * @param string $method
     * @param string $className
     * @return mixed
     */
    public function registerRenderMethod($type,$method,$className) {
        if (!isset($this->xpdo->tvRenders)) $this->xpdo->tvRenders = array();
        if (!isset($this->xpdo->tvRenders[$method])) {
            $this->xpdo->tvRenders[$method] = array();
        }
        $this->xpdo->tvRenders[$method][$type] = $className;
        return $className;
    }

    /**
     * Finds the correct directories for renders
     *
     * @param string $event The plugin event to fire
     * @param string $subdir The subdir to search
     * @return array The found render directories
     */
    public function getRenderDirectories($event,$subdir) {
        $context = $this->xpdo->context->get('key');
        $renderPath = $this->xpdo->getOption('processors_path').'element/tv/renders/'.$context.'/'.$subdir.'/';
        $renderDirectories = array(
            $renderPath,
            $this->xpdo->getOption('processors_path').'element/tv/renders/'.($subdir == 'input' ? 'mgr' : 'web').'/'.$subdir.'/',
        );
        $pluginResult = $this->xpdo->invokeEvent($event,array(
            'context' => $context,
        ));
        $pathsKey = serialize($pluginResult).$context.$event.$subdir;
        /* return cached value if exists */
        if (isset(self::$_renderPaths[$pathsKey])) {
            return self::$_renderPaths[$pathsKey];
        }
        /* process if there is no cached value */
        if (!is_array($pluginResult) && !empty($pluginResult)) { $pluginResult = array($pluginResult); }
        if (!empty($pluginResult)) {
            foreach ($pluginResult as $result) {
                if (empty($result)) continue;
                $renderDirectories[] = $result;
            }
        }

        /* search directories */
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
        self::$_renderPaths[$pathsKey] = array_unique($renderPaths);
        return self::$_renderPaths[$pathsKey];
    }

    /**
     * Check for any Form Customization rules for this TV
     * @param string $value
     * @param modResource $resource
     * @return mixed
     */
    public function checkForFormCustomizationRules($value,&$resource) {
        if ($this->xpdo->request && $this->xpdo->user instanceof modUser) {
            if (empty($resource)) {
                $resource =& $this->xpdo->resource;
            }
            if ($this->xpdo->getOption('form_customization_use_all_groups',null,false)) {
                $userGroups = $this->xpdo->user->getUserGroups();
            } else {
                $primaryGroup = $this->xpdo->user->getPrimaryGroup();
                if ($primaryGroup) {
                    $userGroups = array($primaryGroup->get('id'));
                }
            }
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
            if (!empty($userGroups)) {
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
            }
            if (!empty($this->xpdo->request) && !empty($this->xpdo->request->action)) {
                $wildAction = substr($this->xpdo->request->action, 0, strrpos($this->xpdo->request->action, '/')) . '/*';
                $c->where(array(
                    'modActionDom.action:IN' => array($this->xpdo->request->action, $wildAction),
                ));
            }
            $c->select($this->xpdo->getSelectColumns('modActionDom','modActionDom'));
            $c->select(array(
                'FCSet.constraint_class',
                'FCSet.constraint_field',
                'FCSet.' . $this->xpdo->escape('constraint'),
                'FCSet.template',
            ));
            $c->sortby('FCSet.template','ASC');
            $c->sortby('modActionDom.rank','ASC');
            $domRules = $this->xpdo->getCollection('modActionDom',$c);
            /** @var modActionDom $rule */
            foreach ($domRules as $rule) {
                if (!empty($resource)) {
                    $template = $rule->get('template');
                    if (!empty($template) && $template != $resource->get('template')) {
                        continue;
                    }

                    $constraintClass = $rule->get('constraint_class');
                    if (!empty($constraintClass)) {
                        if (!($resource instanceof $constraintClass)) continue;
                        $constraintField = $rule->get('constraint_field');
                        $constraint = $rule->get('constraint');
                        if ($resource->get($constraintField) != $constraint) {
                            continue;
                        }
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
        return $value;
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
            if (!empty($p[0])) {
                $v = !empty($param[1]) ? $param[1] : 0;
                if ($v == 'true') $v = 1;
                if ($v == 'false') $v = 0;
                $settings[$param[0]] = $v;
            }
        }
        return $settings;
    }

    /**
     * Returns a string or array representation of input options from a source.
     *
     * @param mixed $src A PDOStatement, array or string source to parse.
     * @param string $delim A delimiter for string parsing.
     * @param string $type Type to return, either 'string' or 'array'.
     *
     * @return string|array If delimiter present, returns string, otherwise array.
     */
    public function parseInput($src, $delim= "||", $type= "string") {
        if (is_object($src)) {
            if ($src instanceof PDOStatement) {
                $rs= $src->fetchAll(PDO::FETCH_ASSOC);
                if ($type != "array") {
                    $rows = array();
                    foreach ($rs as $row) {
                        $rows[]= implode(" ", $row);
                    }
                } else {
                    $rows= $rs;
                }
                return ($type == "array" ? $rows : implode($delim, $rows));
            }
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
     * Parses input options sent through post back.
     *
     * @param mixed $v A PDOStatement, array or string to parse.
     * @return mixed The parsed options.
     */
    public function parseInputOptions($v) {
        $a = array();
        if(is_array($v)) return $v;
        else if (is_object($v)) {
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
                    $dbtags['DBASE'] = $dbtags['+dbname'] = $this->xpdo->getOption('dbname');
                    $dbtags['PREFIX'] = $dbtags['+table_prefix'] = $this->xpdo->getOption('table_prefix');
                    foreach($dbtags as $key => $pValue) {
                        if (!is_scalar($pValue)) continue;
                        $param = str_replace('[[+'.$key.']]', (string)$pValue, $param);
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
                    $output = $param;
                    if ($this->xpdo->getOption('allow_tv_eval', null, true)) {
                        $output = eval($param);
                    }
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
                            "AND ResourceGroup.documentgroup = Acl.target " .
                            "ORDER BY Acl.target, Acl.principal, Acl.authority";
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
                            "ORDER BY CategoryClosure.depth DESC, target, principal, authority ASC";
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

    /**
     * Check to see if the
     * @param modUser|null $user
     * @param string $context
     * @return bool
     */
    public function checkResourceGroupAccess($user = null,$context = '') {
        $context = !empty($context) ? $context : '';

        $c = $this->xpdo->newQuery('modResourceGroup');
        $c->innerJoin('modTemplateVarResourceGroup','TemplateVarResourceGroups',array(
            'TemplateVarResourceGroups.documentgroup = modResourceGroup.id',
            'TemplateVarResourceGroups.tmplvarid' => $this->get('id'),
        ));
        $resourceGroups = $this->xpdo->getCollection('modResourceGroup',$c);
        $hasAccess = true;
        if (!empty($resourceGroups)) {
            $hasAccess = false;
            /** @var modResourceGroup $resourceGroup */
            foreach ($resourceGroups as $resourceGroup) {
                if ($resourceGroup->hasAccess($user,$context)) {
                    $hasAccess = true;
                    break;
                }
            }
        }
        return $hasAccess;
    }
}

/**
 * An abstract class meant to be used by TV renders. Do not extend this class directly; use its Input or Output
 * derivatives instead.
 *
 * @package modx
 */
abstract class modTemplateVarRender {
    /** @var modTemplateVar $tv */
    public $tv;
    /** @var modX $modx */
    public $modx;
    /** @var array $config */
    public $config = array();

    function __construct(modTemplateVar $tv,array $config = array()) {
        $this->tv =& $tv;
        $this->modx =& $tv->xpdo;
        $this->config = array_merge($this->config,$config);
    }

    /**
     * Get any lexicon topics for your render. You may override this method in your render to provide an array of
     * lexicon topics to load.
     * @return array
     */
    public function getLexiconTopics() {
        return array('tv_widget');
    }

    /**
     * Render the TV render.
     * @param string $value
     * @param array $params
     * @return mixed|void
     */
    public function render($value,array $params = array()) {
        if (!empty($params)) {
            foreach ($params as $k => $v) {
                if ($v === 'true') {
                    $params[$k] = TRUE;
                } elseif ($v === 'false') {
                    $params[$k] = FALSE;
                } elseif (is_numeric($v) && ((int) $v == $v)) {
                    $params[$k] = intval($v);
                } elseif (is_numeric($v)) {
                    $params[$k] = (float)($v);
                }
            }
        }
        $this->_loadLexiconTopics();
        return $this->process($value,$params);
    }

    /**
     * Load any specified lexicon topics for the render
     */
    protected function _loadLexiconTopics() {
        $topics = $this->getLexiconTopics();
        if (!empty($topics) && is_array($topics)) {
            foreach ($topics as $topic) {
                $this->modx->lexicon->load($topic);
            }
        }
    }

    /**
     * @param string $value
     * @param array $params
     * @return void|mixed
     */
    public function process($value,array $params = array()) {
        return $value;
    }
}
/**
 * An abstract class for extending Output Renders for TVs.
 * @package modx
 */
abstract class modTemplateVarOutputRender extends modTemplateVarRender {}
/**
 * An abstract class for extending Input Renders for TVs.
 * @package modx
 */
abstract class modTemplateVarInputRender extends modTemplateVarRender {
    public function render($value,array $params = array()) {
        $this->setPlaceholder('tv',$this->tv);
        $this->setPlaceholder('id',$this->tv->get('id'));
        $this->setPlaceholder('ctx',isset($_REQUEST['ctx']) ? $_REQUEST['ctx'] : 'web');
        $this->setPlaceholder('params',$params);

        $output = parent::render($value,$params);

        $tpl = $this->getTemplate();
        return !empty($tpl) ? $this->modx->controller->fetchTemplate($tpl) : $output;
    }

    /**
     * Set a placeholder to be used in the template
     * @param string $k
     * @param mixed $v
     */
    public function setPlaceholder($k,$v) {
        $this->modx->controller->setPlaceholder($k,$v);
    }

    /**
     * Return the template path to load
     * @return string
     */
    public function getTemplate() {
        return '';
    }

    /**
     * Return the input options parsed for the TV
     * @return mixed
     */
    public function getInputOptions() {
        return $this->tv->parseInputOptions($this->tv->processBindings($this->tv->get('elements'),$this->modx->resource->get('id')));
    }
}

/**
 * Backwards support for <2.2-style output renders
 * @package modx
 */
class modTemplateVarOutputRenderDeprecated extends modTemplateVarOutputRender {
    /** @var modX $xpdo */
    public $xpdo;

    public function process($value,array $params = array()) {
        $output = '';
        $modx =& $this->modx;
        $this->xpdo =& $this->modx;

        /* simulate hydration */
        $tvArray = $this->tv->toArray();
        foreach ($tvArray as $k => $v) {
            $this->$k = $v;
        }

        $name= $this->tv->get('name');
        $id= "tv$name";
        $format= $this->tv->get('display');
        $tvtype= $this->tv->get('type');
        if (empty($type)) $type = 'default';

        if (!empty($params['modx.renderFile']) && file_exists($params['modx.renderFile'])) {
            $output = include $params['modx.renderFile'];
        }
        return $output;
    }

    public function get($k) {
        return $this->tv->get($k);
    }

    public function set($k,$v) {
        return $this->tv->set($k,$v);
    }
}

/**
 * Backwards support for <2.2-style input renders
 * @package modx
 */
class modTemplateVarInputRenderDeprecated extends modTemplateVarInputRender {
    /** @var modX $xpdo */
    public $xpdo;

    public function process($value,array $params = array()) {
        $this->setPlaceholder('tv',$this->tv);
        $this->setPlaceholder('id',$this->tv->get('id'));
        $this->setPlaceholder('ctx',isset($_REQUEST['ctx']) ? $_REQUEST['ctx'] : 'web');
        $this->setPlaceholder('params',$params);

        $modx =& $this->modx;
        $this->xpdo =& $this->modx;

        /* simulate hydration */
        $tvArray = $this->tv->toArray();
        foreach ($tvArray as $k => $v) {
            $this->$k = $v;
        }

        $name= $this->tv->get('name');
        $id= "tv$name";
        $format= $this->tv->get('display');
        $tvtype= $this->tv->get('type');
        if (empty($type)) $type = 'default';

        $output = '';
        if (!empty($params['modx.renderFile']) && file_exists($params['modx.renderFile'])) {
            $output = include $params['modx.renderFile'];
        }
        return $output;
    }

    public function get($k) {
        return $this->tv->get($k);
    }

    public function set($k,$v) {
        return $this->tv->set($k,$v);
    }
}
