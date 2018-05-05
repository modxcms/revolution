<?php
/**
 * Base controller class for Resources
 *
 * @package modx
 * @subpackage manager.controllers
 */
abstract class ResourceManagerController extends modManagerController {
    public $resourceArray = array();
    public $onDocFormRender = '';
    public $ctx = 'web';
    /** @var modContext $context */
    public $context;
    /** @var modResource $resource */
    public $resource;
    /** @var modResource $resource */
    public $parent = null;
    /** @var string $resourceClass */
    public $resourceClass = 'modDocument';
    /** @var array $tvCounts */
    public $tvCounts = array();
    /** @var array $rteFields */
    public $rteFields = array();

    /** @var modRegister $reg */
    protected $reg;

    public $canPublish = true;
    public $canSave = true;
    public $canDuplicate = true;
    public $canDelete = true;
    public $canEdit = true;
    public $canCreate = true;
    public $canCreateRoot = true;

    /**
     * Return the appropriate Resource controller class based on the class_key request parameter
     *
     * @static
     * @param modX $modx A reference to the modX instance
     * @param string $className The controller class name that is attempting to be loaded
     * @param array $config An array of configuration options for the action
     * @return modManagerController The proper controller class
     */
    public static function getInstance(modX &$modx,$className,array $config = array()) {
        $resourceClass = 'modDocument';
        $isDerivative = false;
        if (!empty($_REQUEST['class_key'])) {
            $isDerivative = true;
            $resourceClass = in_array($_REQUEST['class_key'],array('modDocument','modResource')) ? 'modDocument' : $_REQUEST['class_key'];
            if ($resourceClass == 'modResource') $resourceClass = 'modDocument';
        } else if (!empty($_REQUEST['id']) && $_REQUEST['id'] != 'undefined' && strlen($_REQUEST['id']) === strlen((integer)$_REQUEST['id'])) {
            /** @var modResource $resource */
            $resource = $modx->getObject('modResource', array('id' => $_REQUEST['id']));
            if ($resource && !in_array($resource->get('class_key'),array('modDocument','modResource'))) {
                $isDerivative = true;
                $resourceClass = $resource->get('class_key');
            } else if ($resource && $resource->get('class_key') == 'modResource') { /* fix improper class key */
                $resource->set('class_key','modDocument');
                $resource->save();
            }
        }

        if ($isDerivative) {
            $resourceClass = str_replace(array('../','..','/','\\'),'',$resourceClass);
            if (!class_exists($resourceClass) && !$modx->loadClass($resourceClass)) {
                $resourceClass = 'modDocument';
            }

            $delegateView = $modx->call($resourceClass,'getControllerPath',array(&$modx));
            $action = strtolower(str_replace(array('Resource','ManagerController'),'',$className));
            $className = str_replace('mod','',$resourceClass).ucfirst($action).'ManagerController';
            $controllerFile = $delegateView.$action.'.class.php';
            if (!file_exists($controllerFile)) {
                // We more than likely are using a custom manager theme without overridden controller, let's try with the default theme
                $theme = $modx->getOption('manager_theme', null, 'default');
                $modx->setOption('manager_theme', 'default');
                $delegateView = $modx->call($resourceClass, 'getControllerPath', array(&$modx));
                $controllerFile = $delegateView.$action.'.class.php';
                // Restore custom theme (so we don't process/use default theme assets)
                $modx->setOption('manager_theme', $theme);
            }
            require_once $controllerFile;
        }
        $controller = new $className($modx,$config);
        $controller->resourceClass = $resourceClass;
        return $controller;
    }

    /**
     * Used to set values on the resource record sent to the template for derivative classes
     *
     * @return void
     */
    public function prepareResource() {}

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('resource');
    }

    /**
     * Setup permissions for this page
     * @return void
     */
    public function setPermissions() {
        if ($this->canSave) {
            $this->canSave = $this->resource->checkPolicy('save');
        }
        $this->canEdit = $this->modx->hasPermission('edit_document');
        $this->canCreate = $this->modx->hasPermission('new_document');
        $this->canPublish = $this->modx->hasPermission('publish_document');
        $this->canDelete = ($this->modx->hasPermission('delete_document') && $this->resource->checkPolicy(array('save' => true, 'delete' => true)));
        $this->canDuplicate = ($this->modx->hasPermission('resource_duplicate') &&  $this->resource->checkPolicy('save'));
        $this->canCreateRoot = $this->modx->hasPermission('new_document_in_root');
    }

    /**
     * Get and set the parent for this resource
     * @return string The pagetitle of the parent
     */
    public function setParent() {
        /* handle default parent */
        $parentName = $this->context->getOption('site_name', '', $this->modx->_userConfig);
        $parentId = !empty($this->scriptProperties['parent']) ? $this->scriptProperties['parent'] : $this->resource->get('parent');
        if ($parentId == 0) {
            $parentName = $this->context->getOption('site_name', '', $this->modx->_userConfig);
        } else {
            $this->parent = $this->modx->getObject('modResource',$parentId);
            if ($this->parent !== null) {
                $parentName = $this->parent->get('pagetitle');
                $this->resource->set('parent',$parentId);
            }
        }

        if ($this->parent === null) {
            $this->parent = $this->modx->newObject($this->resourceClass);
            $this->parent->set('id',0);
            $this->parent->set('parent',0);
        }
        return $parentName;
    }

    /**
     * Fire any pre-render events
     * @return array|bool|string
     */
    public function firePreRenderEvents() {
        $resourceId = !empty($this->resource) && ($this->resource instanceof $this->resourceClass) ? $this->resource->get('id') : (!empty($this->scriptProperties['id']) ? $this->scriptProperties['id'] : 0);
        $properties = array(
            'id' => $resourceId,
            'mode' => !empty($resourceId) ? modSystemEvent::MODE_UPD : modSystemEvent::MODE_NEW,
        );
        if (!empty($resourceId)) {
            $properties['resource'] =& $this->resource;
        }
        $onDocFormPrerender = $this->modx->invokeEvent('OnDocFormPrerender',$properties);
        if (is_array($onDocFormPrerender)) {
            $onDocFormPrerender = implode('',$onDocFormPrerender);
        }
        $this->setPlaceholder('onDocFormPrerender',$onDocFormPrerender);
        return $onDocFormPrerender;
    }

    /**
     * Fire any render events
     * @return string
     */
    public function fireOnRenderEvent() {
        $resourceId = $this->resource->get('id');
        $this->onDocFormRender = $this->modx->invokeEvent('OnDocFormRender',array(
            'id' => $resourceId,
            'resource' => &$this->resource,
            'mode' => !empty($resourceId) ? modSystemEvent::MODE_UPD : modSystemEvent::MODE_NEW,
        ));
        if (is_array($this->onDocFormRender)) $this->onDocFormRender = implode('',$this->onDocFormRender);
        $this->onDocFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$this->onDocFormRender);
        $this->setPlaceholder('onDocFormRender',$this->onDocFormRender);
        return $this->onDocFormRender;
    }

    /**
     * Initialize a RichText Editor, if set
     *
     * @return void
     */
    public function loadRichTextEditor() {
        /* register JS scripts */
        $rte = isset($this->scriptProperties['which_editor']) ? $this->scriptProperties['which_editor'] : $this->context->getOption('which_editor', '', $this->modx->_userConfig);
        $this->setPlaceholder('which_editor',$rte);
        /* Set which RTE if not core */
        if ($this->context->getOption('use_editor', false, $this->modx->_userConfig) && !empty($rte)) {
            /* invoke OnRichTextEditorRegister event */
            $textEditors = $this->modx->invokeEvent('OnRichTextEditorRegister');
            $this->setPlaceholder('text_editors',$textEditors);

            $this->rteFields = array('ta');
            $this->setPlaceholder('replace_richtexteditor',$this->rteFields);

            /* invoke OnRichTextEditorInit event */
            $resourceId = $this->resource->get('id');
            $onRichTextEditorInit = $this->modx->invokeEvent('OnRichTextEditorInit',array(
                'editor' => $rte,
                'elements' => $this->rteFields,
                'id' => $resourceId,
                'resource' => &$this->resource,
                'mode' => !empty($resourceId) ? modSystemEvent::MODE_UPD : modSystemEvent::MODE_NEW,
            ));
            if (is_array($onRichTextEditorInit)) {
                $onRichTextEditorInit = implode('',$onRichTextEditorInit);
                $this->setPlaceholder('onRichTextEditorInit',$onRichTextEditorInit);
            }
        }
    }

    /**
     * Get and set the context for this resource
     *
     * @return modContext
     */
    public function setContext() {
        if(!empty($this->scriptProperties['context_key'])) {
            $this->ctx = $this->modx->stripTags($this->scriptProperties['context_key']);
        } else {
            $this->ctx = !empty($this->resource) ? $this->resource->get('context_key') : $this->modx->getOption('default_context');
        }

        $this->context = $this->modx->getContext($this->ctx);
        if (!$this->context) {
            $this->ctx = '';
        }
        $this->setPlaceholder('_ctx',$this->ctx);
        return $this->context;
    }

    /**
     * Load the TVs for the Resource
     *
     * @param array $reloadData resource data passed if reloading
     * @return string The TV editing form
     */
    public function loadTVs($reloadData = array()) {
        $this->setPlaceholder('wctx',$this->resource->get('context_key'));
        $_GET['wctx'] = $this->resource->get('context_key');

        $this->fireOnTVFormRender();

        /* get categories */
        $c = $this->modx->newQuery('modCategory');
        $c->sortby('rank', 'ASC');
        $c->sortby('category','ASC');
        $cats = $this->modx->getCollection('modCategory',$c);
        $categories = array();
        /** @var modCategory $cat */
        foreach ($cats as $cat) {
            $categories[$cat->get('id')] = $cat->toArray();
            $categories[$cat->get('id')]['tvs'] = array();
            $categories[$cat->get('id')]['tvCount'] = 0;
        }

        $categories[0] = array(
            'id' => 0,
            'category' => ucfirst($this->modx->lexicon('uncategorized')),
            'tvs' => array(),
            'tvCount' => 0,
        );
        $tvMap = array();
        $hidden = array();
        $templateId = $this->resource->get('template');
        if ($templateId && ($template = $this->modx->getObject('modTemplate', $templateId))) {
            if ($template) {
                $c = $this->modx->newQuery('modTemplateVar');
                $c->query['distinct'] = 'DISTINCT';
                $c->leftJoin('modCategory','Category');
                $c->innerJoin('modTemplateVarTemplate','TemplateVarTemplate',array(
                    'TemplateVarTemplate.tmplvarid = modTemplateVar.id',
                    'TemplateVarTemplate.templateid' => $templateId,
                ));
                $c->leftJoin('modTemplateVarResource','TemplateVarResource',array(
                    'TemplateVarResource.tmplvarid = modTemplateVar.id',
                    'TemplateVarResource.contentid' => $this->resource->get('id'),
                ));
                $c->select($this->modx->getSelectColumns('modTemplateVar', 'modTemplateVar'));
                $c->select($this->modx->getSelectColumns('modCategory', 'Category', 'cat_', array('category')));
                if(empty($reloadData)) {
                    $c->select($this->modx->getSelectColumns('modTemplateVarResource', 'TemplateVarResource', '', array('value')));
                }
                $c->select($this->modx->getSelectColumns('modTemplateVarTemplate', 'TemplateVarTemplate', '', array('rank')));
                $c->sortby('cat_category,TemplateVarTemplate.rank,modTemplateVar.rank','ASC');
                $tvs = $this->modx->getCollection('modTemplateVar',$c);

                $reloading = !empty($reloadData) && count($reloadData) > 0;
                $this->setPlaceholder('tvcount',count($tvs));
                /** @var modTemplateVar $tv */
                foreach ($tvs as $tv) {
                    if (!$tv->checkResourceGroupAccess(null,'mgr')) {
                        continue;
                    }
                    $v = '';
                    $tv->set('inherited', false);
                    /** @var int $cat */
                    $cat = (int)$tv->get('category');
                    $tvid = $tv->get('id');
                    if($reloading && array_key_exists('tv'.$tvid, $reloadData)) {
                        $v = $reloadData['tv'.$tvid];
                        $tv->set('value', $v);
                    } else {
                        $default = $tv->processBindings($tv->get('default_text'),$this->resource->get('id'));
                        if (strpos($tv->get('default_text'),'@INHERIT') > -1 && (strcmp($default,$tv->get('value')) === 0 || $tv->get('value') === null)) {
                            $tv->set('inherited',true);
                        }
                        if ($tv->get('value') === null) {
                            $v = $default;
                            $tv->set('value',$v);
                        }
                    }

                    if ($tv->get('type') == 'richtext') {
                        $this->rteFields = array_merge($this->rteFields,array(
                            'tv' . $tv->get('id'),
                        ));
                    }
                    $inputForm = $tv->renderInput($this->resource, array('value'=> $v));
                    if (empty($inputForm)) continue;

                    $tv->set('formElement',$inputForm);
                    if ($tv->get('type') != 'hidden') {
                        if (!isset($categories[$cat]['tvs']) || !is_array($categories[$cat]['tvs'])) {
                            $categories[$cat]['tvs'] = array();
                            $categories[$cat]['tvCount'] = 0;
                        }

                        /* add to tv/category map */
                        $tvMap[$tv->get('id')] = $tv->category;

                        /* add TV to category array */
                        $categories[$cat]['tvs'][] = $tv;
                        if ($tv->get('type') != 'hidden') {
                            $categories[$cat]['tvCount']++;
                        }
                    } else {
                        $hidden[] = $tv;
                    }
                }
            }
        }

        $finalCategories = array();
        /** @var modCategory $category */
        foreach ($categories as $n => $category) {
            if (is_array($category)) {
                $category['hidden'] = empty($category['tvCount']) ? true : false;
                $ct = isset($category['tvs']) ? count($category['tvs']) : 0;
                if ($ct > 0) {
                    $finalCategories[$category['id']] = $category;
                    $this->tvCounts[$n] = $ct;
                }
            }
        }

        $onResourceTVFormRender = $this->modx->invokeEvent('OnResourceTVFormRender',array(
            'categories' => &$finalCategories,
            'template' => $templateId,
            'resource' => $this->resource->get('id'),
            'tvCounts' => &$this->tvCounts,
            'hidden' => &$hidden,
        ));
        if (is_array($onResourceTVFormRender)) {
            $onResourceTVFormRender = implode('',$onResourceTVFormRender);
        }
        $this->setPlaceholder('OnResourceTVFormRender',$onResourceTVFormRender);

        $this->setPlaceholder('categories',$finalCategories);
        $this->setPlaceholder('tvCounts',$this->modx->toJSON($this->tvCounts));
        $this->setPlaceholder('tvMap',$this->modx->toJSON($tvMap));
        $this->setPlaceholder('hidden',$hidden);

        if (!empty($this->scriptProperties['showCheckbox'])) {
            $this->setPlaceholder('showCheckbox',1);
        }

        $tvOutput = $this->fetchTemplate('resource/sections/tvs.tpl');
        if (!empty($this->tvCounts)) {
            $this->setPlaceholder('tvOutput',$tvOutput);
        }
        return $tvOutput;
    }

    /**
     * Set token for validating a request
     *
     * @return void
     */
    public function setResourceToken() {
        if(!isset($_SESSION['newResourceTokens']) || !is_array($_SESSION['newResourceTokens'])) {
            $_SESSION['newResourceTokens'] = array();
        }
        $this->resourceArray['create_resource_token'] = uniqid('', true);
        $_SESSION['newResourceTokens'][] = $this->resourceArray['create_resource_token'];
    }

    /**
     * Fire the TV Form Render event
     * @return mixed
     */
    public function fireOnTVFormRender() {
        $onResourceTVFormPrerender = $this->modx->invokeEvent('OnResourceTVFormPrerender',array(
            'resource' => $this->resource->get('id'),
        ));
        if (is_array($onResourceTVFormPrerender)) {
            $onResourceTVFormPrerender = implode('',$onResourceTVFormPrerender);
        }
        $this->setPlaceholder('OnResourceTVFormPrerender',$onResourceTVFormPrerender);
        return $onResourceTVFormPrerender;
    }

    protected function getReloadData() {
        $modx =& $this->modx;
        $scriptProperties =& $this->scriptProperties;
        $reloadData = array();

        // get reload data if reload token found in registry
        if (array_key_exists('reload', $scriptProperties) && !empty($scriptProperties['reload'])) {
            if(!isset($modx->registry)) {
                $modx->getService('registry', 'registry.modRegistry');
            }
            /** @var modRegistry $modx->registry */
            if(isset($modx->registry)) {
                $modx->registry->addRegister('resource_reload', 'registry.modDbRegister', array('directory' => 'resource_reload'));
                $this->reg = $modx->registry->resource_reload;
                if($this->reg->connect()) {
                    $topic = '/resourcereload/' . $scriptProperties['reload'];
                    $this->reg->subscribe($topic);
                    $msgs = $this->reg->read(array('poll_limit'=> 1, 'remove_read'=> true));
                    if(is_array($msgs)) {
                        $reloadData = reset($msgs);
                    }
                    if(!is_array($reloadData)) {
                        $reloadData = array();
                    }
                    $this->reg->unsubscribe($topic);
                }
            }
        }

        return $reloadData;
    }


    public function getResourceGroups() {
        $parentGroups = array();
        if ($this->resource->get('id') == 0) {
            $parent = $this->modx->getObject('modResource',$this->resource->get('parent'));
            /** @var modResource $parent */
            if ($parent) {
                $parentResourceGroups = $parent->getMany('ResourceGroupResources');
                /** @var modResourceGroupResource $parentResourceGroup */
                foreach ($parentResourceGroups as $parentResourceGroup) {
                    $parentGroups[] = $parentResourceGroup->get('document_group');
                }
                $parentGroups = array_unique($parentGroups);
            }
        }

        $this->resourceArray['resourceGroups'] = array();
        $resourceGroups = $this->resource->getGroupsList(array('name' => 'ASC'),0,0);
        /** @var modResourceGroup $resourceGroup */
        foreach ($resourceGroups['collection'] as $resourceGroup) {
            $access = (boolean) $resourceGroup->get('access');
            if (!empty($parent) && $this->resource->get('id') == 0) {
                $access = in_array($resourceGroup->get('id'),$parentGroups) ? true : false;
            }
            $resourceGroupArray = array(
                $resourceGroup->get('id'),
                $resourceGroup->get('name'),
                $access,
            );

            $this->resourceArray['resourceGroups'][] = $resourceGroupArray;
        }
        return $this->resourceArray['resourceGroups'];
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Resources';
    }
}
