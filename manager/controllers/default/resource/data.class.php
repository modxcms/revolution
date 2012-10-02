<?php
require_once dirname(__FILE__).'/resource.class.php';
/**
 * Loads the resource data page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class ResourceDataManagerController extends ResourceManagerController {
    /** @var modResource $resource */
    public $resource;
    /** @var string $previewUrl */
    public $previewUrl;

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('view_document');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.panel.resource.data.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/resource/data.js');
        $this->addHtml('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.ctx = "'.$this->resource->get('context_key').'";
            MODx.load({
                xtype: "modx-page-resource-data"
                ,record: {
                    id: "'.$this->resource->get('id').'"
                    ,context_key: "'.$this->resource->get('context_key').'"
                    ,class_key: "'.$this->resource->get('class_key').'"
                    ,pagetitle: "'.$this->resource->get('pagetitle').'"
                    ,preview_url: "'.$this->previewUrl.'"
                }
                ,canEdit: "'.($this->modx->hasPermission('edit_document') ? 1 : 0).'"
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
        
        $this->resource = $this->modx->getObject('modResource', $scriptProperties['id']);
        if ($this->resource == null) return $this->failure(sprintf($this->modx->lexicon('resource_with_id_not_found'), $scriptProperties['id']));

        if (!$this->resource->checkPolicy('view')) return $this->failure($this->modx->lexicon('access_denied'));

        $this->resource->getOne('CreatedBy');
        $this->resource->getOne('EditedBy');
        $this->resource->getOne('Template');

        $server_offset_time= intval($this->modx->getOption('server_offset_time',null,0));
        $this->resource->set('createdon_adjusted',strftime('%c', $this->resource->get('createdon') + $server_offset_time));
        $this->resource->set('editedon_adjusted',strftime('%c', $this->resource->get('editedon') + $server_offset_time));

        $this->resource->_contextKey= $this->resource->get('context_key');
        $buffer = $this->modx->cacheManager->get($this->resource->getCacheKey(), array(
            xPDO::OPT_CACHE_KEY => $this->modx->getOption('cache_resource_key', null, 'resource'),
            xPDO::OPT_CACHE_HANDLER => $this->modx->getOption('cache_resource_handler', null, $this->modx->getOption(xPDO::OPT_CACHE_HANDLER)),
            xPDO::OPT_CACHE_FORMAT => (integer) $this->modx->getOption('cache_resource_format', null, $this->modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
        ));
        if ($buffer) {
            $placeholders['buffer'] = htmlspecialchars($buffer['resource']['_content']);
        }
        /* assign resource to smarty */
        $placeholders['resource'] = $this->resource;

        /* make preview url */
        $this->getPreviewUrl();
        $placeholders['_ctx'] = $this->resource->get('context_key');

        return $placeholders;
    }

    /**
     * @return string
     */
    public function getPreviewUrl() {
        $this->previewUrl = $this->modx->makeUrl($this->resource->get('id'),$this->resource->get('context_key'));
        return $this->previewUrl;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->resource->get('pagetitle');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'resource/data.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('resource');
    }
}