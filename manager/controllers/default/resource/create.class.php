<?php
require_once dirname(__FILE__).'/resource.class.php';
/**
 * Loads the create resource page
 *
 * @package modx
 * @subpackage manager.controllers
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
        $this->addJavascript($mgrUrl.'assets/modext/util/datetime.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.panel.tv.renders.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.grid.resource.security.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.panel.resource.tv.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.panel.resource.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/resource/create.js');
        $this->addHtml('
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
                ,mode: "create"
            });
        });
        // ]]>
        </script>');
        /* load RTE */
        $this->loadRichTextEditor();
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = array()) {
        $placeholders = array();
        
        /* handle template inheritance */
        if (!empty($this->scriptProperties['parent'])) {
            $this->parent = $this->modx->getObject('modResource',$this->scriptProperties['parent']);
            if (!$this->parent->checkPolicy('add_children')) return $this->failure($this->modx->lexicon('resource_add_children_access_denied'));
        }
        $placeholders['parent'] = $this->parent;

        $this->setContext();
        if (!$this->context) { return $this->failure($this->modx->lexicon('context_err_nf')); }

        /* handle custom resource types */
        $this->resource = $this->modx->newObject($this->resourceClass);
        $this->resource->set('id',0);
        $this->resource->set('context_key',$this->context->get('key'));
        $placeholders['resource'] = $this->resource;
        $this->resourceArray = array();
        
        $placeholders['parentname'] = $this->setParent();
        $this->fireOnRenderEvent();

        /* check permissions */
        $this->setPermissions();

        /* set default template */
        $defaultTemplate = $this->getDefaultTemplate();
        $this->resourceArray = array_merge($this->resourceArray,array(
            'template' => $defaultTemplate,
            'content_type' => $this->context->getOption('default_content_type',1,$this->modx->_userConfig),
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
        $overridden = $this->checkFormCustomizationRules($this->parent,true);
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

        if (!empty($this->resourceArray['parent'])) {
            if ($this->parent->get('id') == $this->resourceArray['parent']) {
                $this->resourceArray['parent_pagetitle'] = $this->parent->get('pagetitle');
            } else {
                $overriddenParent = $this->modx->getObject('modResource',$this->resourceArray['parent']);
                if ($overriddenParent) {
                    $this->resourceArray['parent_pagetitle'] = $overriddenParent->get('pagetitle');
                }
            }
        }

        /* get TVs */
        $this->loadTVs();

        /* single-use token for creating resource */
        $this->setResourceToken();

        return $placeholders;
    }

    /**
     * Return the default template for this resource
     *
     * @return int
     */
    public function getDefaultTemplate() {
        $defaultTemplate = (isset($this->scriptProperties['template']) ? $this->scriptProperties['template'] : (!empty($this->parent->id) ? $this->parent->get('template') : $this->context->getOption('default_template', 0, $this->modx->_userConfig)));
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