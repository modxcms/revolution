<?php

use MODX\Revolution\modManagerController;

/**
 * Read-only registry view for deployment-owned disk definitions.
 */
class SystemDefinitionsManagerController extends modManagerController
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('view_element');
    }

    public function process(array $scriptProperties = [])
    {
    }

    public function loadCustomCssJs()
    {
        $this->addJavascript($this->modx->getOption('manager_url', null, MODX_MANAGER_URL)
            . 'assets/modext/sections/system/definitions.js');
        $this->addHtml('<script>Ext.onReady(function(){MODx.load({xtype:"modx-page-system-definitions",releaseHash:'
            . $this->modx->toJSON($this->modx->getDefinitionRegistry()->getReleaseHash()) . '});});</script>');
    }

    public function getPageTitle()
    {
        return $this->modx->lexicon('definition_registry');
    }

    public function getTemplateFile()
    {
        return '';
    }

    public function getLanguageTopics()
    {
        return ['definition_registry'];
    }
}
