<?php
/**
 * @package modx
 */
/**
 * Loads the Media Sources page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SourceUpdateManagerController extends modManagerController {
    /** @var modMediaSource $source */
    public $source;
    public $sourceArray = array();
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('source_edit');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/core/modx.grid.local.property.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/source/modx.grid.source.properties.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/source/modx.grid.source.access.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/source/modx.panel.source.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/source/update.js');
        $this->addHtml('<script type="text/javascript">Ext.onReady(function() {MODx.load({
    xtype: "modx-page-source-update"
    ,record: '.$this->modx->toJSON($this->sourceArray).'
});});</script>');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = array()) {
        if (empty($this->scriptProperties['id'])) return $this->failure($this->modx->lexicon('source_err_ns'));
        $this->source = $this->modx->getObject('sources.modMediaSource',$this->scriptProperties['id']);
        if (empty($this->source)) return $this->failure($this->modx->lexicon('source_err_nf'));

        $this->sourceArray = $this->source->toArray();
        $this->getProperties();
        $this->getAccess();

        return array();
    }

    public function getProperties() {
        $properties = $this->source->getProperties();
        $data = array();
        foreach ($properties as $property) {
            $data[] = array(
                $property['name'],
                $property['desc'],
                $property['type'],
                !empty($property['options']) ? $property['options'] : array(),
                $property['value'],
                $property['lexicon'],
                !empty($property['overridden']) ? $property['overridden'] : 0,
                $property['desc_trans'],
                $property['name_trans'],
            );
        }
        $this->sourceArray['properties'] = $data;
    }

    public function getAccess() {
        $c = $this->modx->newQuery('sources.modAccessMediaSource');
        $c->innerJoin('sources.modMediaSource','Target');
        $c->innerJoin('modAccessPolicy','Policy');
        $c->innerJoin('modUserGroup','Principal');
        $c->innerJoin('modUserGroupRole','MinimumRole');
        $c->where(array(
            'target' => $this->source->get('id'),
        ));
        $c->select($this->modx->getSelectColumns('sources.modAccessMediaSource','modAccessMediaSource'));
        $c->select(array(
            'target_name' => 'Target.name',
            'principal_name' => 'Principal.name',
            'policy_name' => 'Policy.name',
            'authority_name' => 'MinimumRole.name',
        ));
        $acls = $this->modx->getCollection('sources.modAccessMediaSource',$c);
        $access = array();
        /** @var modAccessMediaSource $acl */
        foreach ($acls as $acl) {
            $access[] = array(
                $acl->get('id'),
                $acl->get('target'),
                $acl->get('target_name'),
                $acl->get('principal_class'),
                $acl->get('principal'),
                $acl->get('principal_name'),
                $acl->get('authority'),
                $acl->get('authority_name'),
                $acl->get('policy'),
                $acl->get('policy_name'),
                $acl->get('context_key'),
            );
        }

        $this->sourceArray['access'] = $this->modx->toJSON($access);
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('source_update');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'source/update.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('source','namespace','propertyset');
    }
}