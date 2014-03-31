<?php
/**
 * @package modx
 * @subpackage manager.controllers
 */
class SystemEventManagerController extends modManagerController {
    public $logArray = array();

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('error_log_view');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/system/modx.panel.error.log.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/system/error.log.js');
        $this->addHtml('<script type="text/javascript">
        MODx.hasEraseErrorLog = "'.($this->modx->hasPermission('error_log_erase') ? 1 : 0).'"
        Ext.onReady(function() {
            MODx.load({
              xtype: "modx-page-error-log"
              ,record: '.$this->modx->toJSON($this->logArray).'
            });
        });
        </script>');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = array()) {
        $f = $this->modx->getOption(xPDO::OPT_CACHE_PATH).'logs/error.log';
        $this->logArray['name'] = $f;
        if (file_exists($f)) {
            $this->logArray['size'] = round(@filesize($f) / 1000 / 1000,2);
            if ($this->logArray['size'] > 1) {
                $this->logArray['log'] = '';
                $this->logArray['tooLarge'] = true;
                $this->logArray['size'] .= ' MiB';
            } else {
                $this->logArray['log'] = @file_get_contents($f);
                $this->logArray['tooLarge'] = false;
            }
        }
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('error_log');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return '';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('system_events');
    }
}
