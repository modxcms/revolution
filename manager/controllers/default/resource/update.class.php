<?php
/**
 * Loads the update resource page
 *
 * @package modx
 * @subpackage manager.resource
 */
require_once dirname(__FILE__).'/resource.class.php';
class ResourceUpdateManagerController extends ResourceManagerController {
    public $canSave = true;
    public $locked = false;
    public $lockedText = '';
    public $previewUrl = '';

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $managerUrl = $this->context->getOption('manager_url', MODX_MANAGER_URL, $this->modx->_userConfig);
        $this->addJavascript($managerUrl.'assets/modext/util/datetime.js');
        $this->addJavascript($managerUrl.'assets/modext/widgets/element/modx.panel.tv.renders.js');
        $this->addJavascript($managerUrl.'assets/modext/widgets/resource/modx.grid.resource.security.js');
        $this->addJavascript($managerUrl.'assets/modext/widgets/resource/modx.panel.resource.tv.js');
        $this->addJavascript($managerUrl.'assets/modext/widgets/resource/modx.panel.resource.js');
        $this->addJavascript($managerUrl.'assets/modext/sections/resource/update.js');
        $this->addHtml('
        <script type="text/javascript">
        // <![CDATA[
        MODx.config.publish_document = "'.$this->canPublish.'";
        MODx.onDocFormRender = "'.$this->onDocFormRender.'";
        MODx.ctx = "'.$this->resource->get('context_key').'";
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-resource-update"
                ,resource: "'.$this->resource->get('id').'"
                ,record: '.$this->modx->toJSON($this->resourceArray).'
                ,access_permissions: "'.$this->showAccessPermissions.'"
                ,publish_document: "'.$this->canPublish.'"
                ,preview_url: "'.$this->previewUrl.'"
                ,locked: '.($this->locked ? 1 : 0).'
                ,lockedText: "'.$this->lockedText.'"
                ,canSave: '.($this->canSave ? 1 : 0).'
                ,canEdit: "'.($this->modx->hasPermission('edit_document') ? 1 : 0).'"
                ,canCreate: "'.($this->modx->hasPermission('new_document') ? 1 : 0).'"
                ,canDelete: "'.($this->modx->hasPermission('delete_document') ? 1 : 0).'"
                ,show_tvs: '.(!empty($this->tvCounts) ? 1 : 0).'
            });
        });
        // ]]>
        </script>');
    }

    public function process(array $scriptProperties = array()) {
        $placeholders = array();
        
        if (empty($scriptProperties['id'])) return $this->failure($this->modx->lexicon('resource_err_nf'));
        $this->resource = $this->modx->getObject('modResource',$scriptProperties['id']);
        if (empty($this->resource)) return $this->failure($this->modx->lexicon('resource_err_nfs',array('id' => $scriptProperties['id'])));

        if (!$this->resource->checkPolicy('save')) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        /* get context */
        $this->setContext();

        /* check for locked status */
        $this->checkForLocks();

        /* set template overrides */
        if (isset($scriptProperties['template'])) $this->resource->set('template',$scriptProperties['template']);

        $this->setParent();

        /* invoke OnDocFormRender event */
        $this->fireOnRenderEvent();

        /* check permissions */
        $this->setPermissions();

        /* load RTE */
        $this->loadRichTextEditor();

        /* register FC rules */
        $this->resourceArray = $this->resource->toArray();
        $overridden = $this->checkFormCustomizationRules($this->resource);
        $this->resourceArray = array_merge($this->resourceArray,$overridden);

        $this->resourceArray['parent_pagetitle'] = $this->parent ? $this->parent->get('pagetitle') : '';

        $this->resourceArray['published'] = intval($this->resourceArray['published']) == 1 ? true : false;
        $this->resourceArray['hidemenu'] = intval($this->resourceArray['hidemenu']) == 1 ? true : false;
        $this->resourceArray['isfolder'] = intval($this->resourceArray['isfolder']) == 1 ? true : false;
        $this->resourceArray['richtext'] = intval($this->resourceArray['richtext']) == 1 ? true : false;
        $this->resourceArray['searchable'] = intval($this->resourceArray['searchable']) == 1 ? true : false;
        $this->resourceArray['cacheable'] = intval($this->resourceArray['cacheable']) == 1 ? true : false;
        $this->resourceArray['deleted'] = intval($this->resourceArray['deleted']) == 1 ? true : false;
        $this->resourceArray['uri_override'] = intval($this->resourceArray['uri_override']) == 1 ? true : false;

        /* get TVs */
        $this->resource->set('template',$this->resourceArray['template']);

        $this->prepareResource();
        $this->loadTVs();
        
        /* get url for resource for preview window */
        $this->previewUrl = $this->modx->makeUrl($this->resource->get('id'),'','','full');

        $this->setPlaceholder('resource',$this->resource);
        return $placeholders;
    }

    /**
     * Check for locks on the Resource
     * 
     * @return bool
     */
    public function checkForLocks() {
        $lockedBy = $this->resource->addLock($this->modx->user->get('id'));
        $this->canSave = $this->modx->hasPermission('save_document') ? 1 : 0;
        $this->locked = false;
        $this->lockedText = '';
        if (!empty($lockedBy) && $lockedBy !== true) {
            $this->canSave = false;
            $this->locked = true;
            $locker = $this->modx->getObject('modUser',$lockedBy);
            if ($locker) {
                $lockedBy = $locker->get('username');
            }
            $this->lockedText = $this->modx->lexicon('resource_locked_by', array('user' => $lockedBy, 'id' => $this->resource->get('id')));
        }
        return $this->locked;
    }
    
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('edit_document');
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('editing',array('name'  => $this->resourceArray['pagetitle']));
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'resource/update.tpl';
    }
}
