<?php
require_once dirname(__FILE__).'/resource.class.php';
/**
 * Loads the create resource page
 *
 * @package modx
 * @subpackage manager.resource
 */
class ResourceCreateManagerController extends ResourceManagerController {
    
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('new_document');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/util/datetime.js');
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/element/modx.panel.tv.renders.js');
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/resource/modx.grid.resource.security.js');
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/resource/modx.panel.resource.tv.js');
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/resource/modx.panel.resource.js');
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/sections/resource/create.js');
        $this->modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        // <![CDATA[
        MODx.config.publish_document = "'.$this->canPublish.'";
        MODx.onDocFormRender = "'.$this->onDocFormRender.'";
        MODx.ctx = "'.$this->ctx.'";
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-resource-create"
                ,record: '.$this->modx->toJSON($this->resourceArray).'
                ,access_permissions: "'.$this->showAccessPermissions.'"
                ,publish_document: "'.$this->canPublish.'"
                ,canSave: "'.($this->modx->hasPermission('save_document') ? 1 : 0).'"
                ,show_tvs: '.(!empty($this->tvCounts) ? 1 : 0).'
            });
        });
        // ]]>
        </script>');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = array()) {
        $placeholders = array();
        
        /* handle template inheritance */
        if (!empty($scriptProperties['parent'])) {
            $this->parent = $this->modx->getObject('modResource',$scriptProperties['parent']);
            if (!$this->parent->checkPolicy('add_children')) return $this->failure($this->modx->lexicon('resource_add_children_access_denied'));
        }
        $placeholders['parent'] = $this->parent;

        $this->setContext();
        if (!$this->context) { return $this->failure($this->modx->lexicon('context_err_nf')); }

        /* handle custom resource types */
        $this->resource = $this->modx->newObject($this->resourceClass);
        $placeholders['resource'] = $this->resource;
        $this->resourceArray = array();
        
        $placeholders['parentname'] = $this->setParent();
        $placeholders['onDocFormRender'] = $this->fireOnRenderEvent();

        /* check permissions */
        $this->setPermissions();

        $this->loadRichTextEditor($scriptProperties);

        /* set default template */
        $defaultTemplate = $this->getDefaultTemplate($scriptProperties);
        $this->resourceArray = array_merge($this->resourceArray,array(
            'template' => $defaultTemplate,
            'content_type' => 1,
            'class_key' => $this->resourceClass,
            'context_key' => $this->ctx,
            'parent' => $this->parent->get('id'),
            'richtext' =>  $this->context->getOption('richtext_default', true, $this->modx->_userConfig),
            'hidemenu' => $this->context->getOption('hidemenu_default', 0, $this->modx->_userConfig),
            'published' => $this->context->getOption('publish_default', 0, $this->modx->_userConfig),
            'searchable' => $this->context->getOption('search_default', 1, $this->modx->_userConfig),
            'cacheable' => $this->context->getOption('cache_default', 1, $this->modx->_userConfig),
        ));
        $this->parent->fromArray($this->resourceArray);
        $this->parent->set('template',$defaultTemplate);
        $this->resource->set('template',$defaultTemplate);

        /* handle FC rules */
        $overridden = $this->checkFormCustomizationRules($parent,true);
        $this->resourceArray = array_merge($this->resourceArray,$overridden);

        /* handle checkboxes and defaults */
        $this->resourceArray['published'] = intval($this->resourceArray['published']) == 1 ? true : false;
        $this->resourceArray['hidemenu'] = intval($this->resourceArray['hidemenu']) == 1 ? true : false;
        $this->resourceArray['isfolder'] = intval($this->resourceArray['isfolder']) == 1 ? true : false;
        $this->resourceArray['richtext'] = intval($this->resourceArray['richtext']) == 1 ? true : false;
        $this->resourceArray['searchable'] = intval($this->resourceArray['searchable']) == 1 ? true : false;
        $this->resourceArray['cacheable'] = intval($this->resourceArray['cacheable']) == 1 ? true : false;
        $this->resourceArray['deleted'] = intval($this->resourceArray['deleted']) == 1 ? true : false;
        $this->resourceArray['uri_override'] = intval($this->resourceArray['uri_override']) == 1 ? true : false;
        $this->resourceArray['parent_pagetitle'] = $this->parent->get('pagetitle');

        /* get TVs */
        $this->loadTVs($scriptProperties);

        /* single-use token for creating resource */
        $this->setResourceToken();

        return $placeholders;
    }

    /**
     * Return the default template for this resource
     * 
     * @param array $scriptProperties
     * @return int
     */
    public function getDefaultTemplate(array $scriptProperties = array()) {
        $defaultTemplate = (isset($scriptProperties['template']) ? $scriptProperties['template'] : (!empty($this->parent->id) ? $this->parent->get('template') : $this->context->getOption('default_template', 0, $this->modx->_userConfig)));
        $userGroups = $this->modx->user->getUserGroups();
        $c = $this->modx->newQuery('modActionDom');
        $c->innerJoin('modFormCustomizationSet','FCSet');
        $c->innerJoin('modFormCustomizationProfile','Profile','FCSet.profile = Profile.id');
        $c->leftJoin('modFormCustomizationProfileUserGroup','ProfileUserGroup','Profile.id = ProfileUserGroup.profile');
        $c->leftJoin('modFormCustomizationProfile','UGProfile','UGProfile.id = ProfileUserGroup.profile');
        $c->where(array(
            'modActionDom.action' => $this->action['id'],
            'modActionDom.name' => 'template',
            'modActionDom.container' => 'modx-panel-resource',
            'modActionDom.rule' => 'fieldDefault',
            'modActionDom.active' => true,
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
        $fcDt = $this->modx->getObject('modActionDom',$c);
        if ($fcDt) {
            if ($this->parent) { /* ensure get all parents */
                $p = $this->parent ? $this->parent->get('id') : 0;
                $parentIds = $this->modx->getParentIds($p,10,array(
                    'context' => $this->parent->get('context_key'),
                ));
                $parentIds[] = $p;
                $parentIds = array_unique($parentIds);
            } else {
                $parentIds = array(0);
            }

            $constraintField = $fcDt->get('constraint_field');
            if (($constraintField == 'id' || $constraintField == 'parent') && in_array($fcDt->get('constraint'),$parentIds)) {
                $defaultTemplate = $fcDt->get('value');
            } else if (empty($constraintField)) {
                $defaultTemplate = $fcDt->get('value');
            }
        }
        return $defaultTemplate;
    }

    /**
     * Get and set the parent for this resource
     * @return string The pagetitle of the parent
     */
    public function setParent() {
        /* handle default parent */
        $parentName = $this->context->getOption('site_name', '', $this->modx->_userConfig);
        $this->resource->set('parent',0);
        if (isset ($scriptProperties['parent'])) {
            if ($scriptProperties['parent'] == 0) {
                $parentName = $this->context->getOption('site_name', '', $this->modx->_userConfig);
            } else {
                $this->parent = $this->modx->getObject('modResource',$scriptProperties['parent']);
                if ($this->parent != null) {
                    $parentName = $this->parent->get('pagetitle');
                    $this->resource->set('parent',$this->parent->get('id'));
                }
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
     * Initialize a RichText Editor, if set
     * 
     * @param array $scriptProperties
     * @return void
     */
    public function loadRichTextEditor(array $scriptProperties = array()) {
        /* register JS scripts */
        $rte = isset($scriptProperties['which_editor']) ? $scriptProperties['which_editor'] : $this->context->getOption('which_editor', '', $this->modx->_userConfig);
        $this->setPlaceholder('which_editor',$rte);
        /* Set which RTE if not core */
        if ($this->context->getOption('use_editor', false, $this->modx->_userConfig) && !empty($rte)) {
            /* invoke OnRichTextEditorRegister event */
            $textEditors = $this->modx->invokeEvent('OnRichTextEditorRegister');
            $this->setPlaceholder('text_editors',$textEditors);

            $this->rteFields = array('ta');
            $this->setPlaceholder('replace_richtexteditor',$this->rteFields);

            /* invoke OnRichTextEditorInit event */
            $onRichTextEditorInit = $this->modx->invokeEvent('OnRichTextEditorInit',array(
                'editor' => $rte,
                'elements' => $this->rteFields,
                'id' => 0,
                'mode' => modSystemEvent::MODE_NEW,
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
        $this->ctx = !empty($scriptProperties['context_key']) ? $scriptProperties['context_key'] : 'web';
        $this->setPlaceholder('_ctx',$this->ctx);
        $this->context = $this->modx->getContext($this->ctx);
        return $this->context;
    }

    /**
     * Set the Resource token for creating a resource
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
     * Fire any pre-render events
     * @return array|bool|string
     */
    public function firePreRenderEvents() {
        $onDocFormPrerender = $this->modx->invokeEvent('OnDocFormPrerender',array(
            'id' => 0,
            'mode' => modSystemEvent::MODE_NEW,
        ));
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
        $this->onDocFormRender = $this->modx->invokeEvent('OnDocFormRender',array(
            'id' => 0,
            'mode' => modSystemEvent::MODE_NEW,
        ));
        if (is_array($this->onDocFormRender)) $this->onDocFormRender = implode('',$this->onDocFormRender);
        $this->onDocFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$this->onDocFormRender);
        return $this->onDocFormRender;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('document_new');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'resource/create.tpl';
    }

}