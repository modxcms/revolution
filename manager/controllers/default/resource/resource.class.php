<?php
/**
 * Base controller class for Resources
 * 
 * @package modx
 */
abstract class ResourceManagerController extends modManagerController {
    public $resourceArray = array();
    public $showAccessPermissions = true;
    public $canPublish = true;
    public $onDocFormRender = '';
    public $ctx = 'web';
    public $resource;
    public $parent;
    public $context;
    public $resourceClass = 'modDocument';
    public $tvCounts = array();
    public $rteFields = array();

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
        if (!empty($_REQUEST['class_key']) && !in_array($_REQUEST['class_key'],array('modDocument','modResource'))) {
            $isDerivative = true;
            $resourceClass = $_REQUEST['class_key'];
        } else if (!empty($_REQUEST['id'])) {
            $resource = $modx->getObject('modResource',$_REQUEST['id']);
            if ($resource && !in_array($resource->get('class_key'),array('modDocument','modResource'))) {
                $isDerivative = true;
                $resourceClass = $resource->get('class_key');
            }
        }

        if ($isDerivative) {
            $resourceClass = str_replace(array('../','..','/','\\'),'',$resourceClass);

            $delegateView = $modx->call($resourceClass,'getControllerPath',array(&$modx));
            $action = strtolower(str_replace(array('Resource','ManagerController'),'',$className));
            $className = str_replace('mod','',$resourceClass).ucfirst($action).'ManagerController';
            $controllerFile = $delegateView.$action.'.class.php';
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
        $this->canPublish = $this->modx->hasPermission('publish_document');
        $this->showAccessPermissions = $this->modx->hasPermission('access_permissions');
    }
    
    /**
     * Get and set the parent for this resource
     * @return string The pagetitle of the parent
     */
    public function setParent() {
        /* handle default parent */
        $parentName = $this->context->getOption('site_name', '', $this->modx->_userConfig);
        $parentId = !empty($scriptProperties['parent']) ? $scriptProperties['parent'] : $this->resource->get('parent');
        if ($parentId == 0) {
            $parentName = $this->context->getOption('site_name', '', $this->modx->_userConfig);
        } else {
            $this->parent = $this->modx->getObject('modResource',$parentId);
            if ($this->parent != null) {
                $parentName = $this->parent->get('pagetitle');
                $this->resource->set('parent',$parentId);
            }
        }

        if ($this->parent == null) {
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
        $resourceId = $this->resource->get('id');
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
        $this->ctx = !empty($this->scriptProperties['context_key']) ? $this->scriptProperties['context_key'] : 'web';
        $this->setPlaceholder('_ctx',$this->ctx);
        $this->context = $this->modx->getContext($this->ctx);
        return $this->context;
    }

    /**
     * Load the TVs for the Resource
     *
     * @return string The TV editing form
     */
    public function loadTVs() {
        $this->setPlaceholder('wctx',$this->resource->get('context_key'));
        $_GET['wctx'] = $this->resource->get('context_key');

        $this->fireOnTVFormRender();

        /* get categories */
        $c = $this->modx->newQuery('modCategory');
        $c->sortby('category','ASC');
        $categories = $this->modx->getCollection('modCategory',$c);
        $emptyCategory = $this->modx->newObject('modCategory');
        $emptyCategory->set('category',ucfirst($this->modx->lexicon('uncategorized')));
        $emptyCategory->id = 0;
        $categories[0] = $emptyCategory;
        $tvMap = array();
        $templateId = $this->resource->get('template');
        if ($templateId && ($template = $this->modx->getObject('modTemplate', $templateId))) {
            $tvs = array();
            if ($template) {
                $c = $this->modx->newQuery('modTemplateVar');
                $c->query['distinct'] = 'DISTINCT';
                $c->select($this->modx->getSelectColumns('modTemplateVar', 'modTemplateVar'));
                $c->select($this->modx->getSelectColumns('modCategory', 'Category', 'cat_', array('category')));
                $c->select($this->modx->getSelectColumns('modTemplateVarResource', 'TemplateVarResource', '', array('value')));
                $c->select($this->modx->getSelectColumns('modTemplateVarTemplate', 'TemplateVarTemplate', '', array('rank')));
                $c->leftJoin('modCategory','Category');
                $c->innerJoin('modTemplateVarTemplate','TemplateVarTemplate',array(
                    'TemplateVarTemplate.tmplvarid = modTemplateVar.id',
                    'TemplateVarTemplate.templateid' => $templateId,
                ));
                $c->leftJoin('modTemplateVarResource','TemplateVarResource',array(
                    'TemplateVarResource.tmplvarid = modTemplateVar.id',
                    'TemplateVarResource.contentid' => $this->resource->get('id'),
                ));
                $c->sortby('cat_category,TemplateVarTemplate.rank,modTemplateVar.rank','ASC');
                $tvs = $this->modx->getCollection('modTemplateVar',$c);

                $this->modx->smarty->assign('tvcount',count($tvs));
                foreach ($tvs as $tv) {
                    $cat = (int)$tv->get('category');
                    $default = $tv->processBindings($tv->get('default_text'),$this->resource->get('id'));
                    if (strpos($tv->get('default_text'),'@INHERIT') > -1 && (strcmp($default,$tv->get('value')) == 0 || $tv->get('value') == null)) {
                        $tv->set('inherited',true);
                    }
                    if ($tv->get('value') == null) {
                        $v = $tv->get('default_text');
                        if ($tv->get('type') == 'checkbox' && $tv->get('value') == '') {
                            $v = '';
                        }
                        $tv->set('value',$v);
                    }

                    if ($tv->get('type') == 'richtext') {
                        $this->rteFields = array_merge($this->rteFields,array(
                            'tv' . $tv->id,
                        ));
                    }
                    $inputForm = $tv->renderInput($this->resource->get('id'));
                    if (empty($inputForm)) continue;

                    $tv->set('formElement',$inputForm);
                    if (!is_array($categories[$cat]->tvs)) {
                        $categories[$cat]->tvs = array();
                        $categories[$cat]->tvCount = 0;
                    }

                    /* add to tv/category map */
                    $tvMap[$tv->id] = $tv->category;

                    /* add TV to category array */
                    $categories[$cat]->tvs[] = $tv;
                    if ($tv->get('type') != 'hidden') {
                        $categories[$cat]->tvCount++;
                    }
                }
            }
        }

        $this->tvCounts = array();
        $finalCategories = array();
        foreach ($categories as $n => $category) {
            $category->hidden = empty($category->tvCount) ? true : false;
            $ct = count($category->tvs);
            if ($ct > 0) {
                $finalCategories[$category->get('id')] = $category;
                $this->tvCounts[$n] = $ct;
            }
        }
        
        $onResourceTVFormRender = $this->modx->invokeEvent('OnResourceTVFormRender',array(
            'categories' => &$finalCategories,
            'template' => $templateId,
            'resource' => $this->resource->get('id'),
            'tvCounts' => &$this->tvCounts,
        ));
        if (is_array($onResourceTVFormRender)) {
            $onResourceTVFormRender = implode('',$onResourceTVFormRender);
        }
        $this->setPlaceholder('OnResourceTVFormRender',$onResourceTVFormRender);

        $this->setPlaceholder('categories',$finalCategories);
        $this->setPlaceholder('tvCounts',$this->modx->toJSON($this->tvCounts));
        $this->setPlaceholder('tvMap',$this->modx->toJSON($tvMap));

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
}
