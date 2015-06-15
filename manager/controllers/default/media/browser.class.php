<?php

/**
 * "Media Browser" controller
 */
class MediaBrowserManagerController extends modManagerController
{
    /**
     * @inherit
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('file_manager');
    }

    /**
     * Register custom CSS/JS for the page
     *
     * @return void
     */
    public function loadCustomCssJs()
    {
        // $mgrUrl = $this->modx->getOption('manager_url', null, MODX_MANAGER_URL);
        // $this->addJavascript($mgrUrl . 'assets/modext/widgets/media/browser.js');

        $this->addHtml(
<<<HTML
<script type="text/javascript">
// <![CDATA[
    Ext.onReady(function() {
        Ext.getCmp('modx-layout').hideLeftbar(true, false);
        MODx.add('modx-media-view');
    });
// ]]>
</script>
HTML
        );
    }

    /**
     * @inherit
     */
    public function process(array $scriptProperties = array())
    {
        return array();
    }

    /**
     * @inherit
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('modx_browser');
    }

    /**
     * @inherit
     */
    public function getTemplateFile()
    {
        return '';
    }

    /**
     * @inherit
     */
    public function getLanguageTopics()
    {
        return array('file');
    }
}
