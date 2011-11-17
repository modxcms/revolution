<?php
require_once MODX_CORE_PATH.'model/modx/modmanagercontroller.class.php';
/**
 * Grabs a list of input properties for a TV type
 *
 * @param string $context (optional) The context by which to grab renders from. Defaults to
 * executing context.
 * @param string $type (optional) The type of render to grab properties for.
 * Defaults to default.
 * @param integer $tv (optional) The TV to prefill property values from.
 *
 * @package modx
 * @subpackage processors.element.tv.renders
 */
class modTvRendersGetPropertiesProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('view_tv');
    }
    public function getLanguageTopics() {
        return array('tv_widget','tv_input_types');
    }

    public function initialize() {
        /* simulate controller to allow controller methods in TV Input Properties controllers */
        $this->modx->getService('smarty', 'smarty.modSmarty','');

        $context = $this->getProperty('context');
        if (empty($context)) {
            $this->setProperty('context',$this->modx->context->get('key'));
        }
        $this->setDefaultProperties(array(
            'type' => 'default',
        ));
        return true;
    }

    public function process() {
        $this->renderController();
        $this->getInputProperties();

        $renderDirectories = $this->getRenderDirectories();
        return $this->getRenderOutput($renderDirectories);
    }

    /**
     * Get the properties render output when given an array of directories to search
     * @param array $renderDirectories
     * @return mixed|string
     */
    public function getRenderOutput(array $renderDirectories) {
        $o = '';
        foreach ($renderDirectories as $renderDirectory) {
            if (empty($renderDirectory) || !is_dir($renderDirectory)) continue;

            $renderFile = $renderDirectory.$this->getProperty('type').'.php';
            if (file_exists($renderFile)) {
                $modx =& $this->modx;
                @ob_start();
                $o = include $renderFile;
                @ob_end_clean();
                break;
            }
        }
        return $o;
    }

    /**
     * Simulate controller with the faux controller class
     * @return string
     */
    public function renderController() {
        $c = new TvInputPropertiesManagerController($this->modx);
        $this->modx->controller = call_user_func_array(array($c,'getInstance'),array($this->modx,'TvInputPropertiesManagerController'));
        return $this->modx->controller->render();
    }

    /**
     * Get default display properties for specific tv
     * @return array
     */
    public function getInputProperties() {
        $settings = array();
        $tvId = $this->getProperty('tv');
        if (!empty($tvId)) {
            /** @var modTemplateVar $tv */
            $tv = $this->modx->getObject('modTemplateVar',$tvId);
            if (is_object($tv) && $tv instanceof modTemplateVar) {
                $settings = $tv->get('input_properties');

            }
            $this->modx->controller->setPlaceholder('tv',$tvId);
        }
        if (!isset($settings['allowBlank'])) $settings['allowBlank'] = true;
        $this->modx->controller->setPlaceholder('params',$settings);
        return $settings;
    }

    /**
     * @return array
     */
    public function getRenderDirectories() {
        /* handle dynamic paths */
        $renderDirectories = array(
            dirname(__FILE__).'/'.$this->getProperty('context').'/inputproperties/',
        );

        /* allow for custom directories */
        $pluginResult = $this->modx->invokeEvent('OnTVInputPropertiesList',array(
            'context' => $this->getProperty('context'),
        ));
        if (!is_array($pluginResult) && !empty($pluginResult)) { $pluginResult = array($pluginResult); }
        if (!empty($pluginResult)) {
            $renderDirectories = array_merge($renderDirectories,$pluginResult);
        }
        return $renderDirectories;
    }
}

/**
 * Faux controller class for rendering TV input properties
 */
class TvInputPropertiesManagerController extends modManagerController {
    public $loadFooter = false;
    public $loadHeader = false;
    public function checkPermissions() {
        return $this->modx->hasPermission('view_tv');
    }
    public function loadCustomCssJs() {}
    public function process(array $scriptProperties = array()) {}
    public function getPageTitle() {return '';}
    public function getTemplateFile() {
        return 'empty.tpl';
    }
    public function getLanguageTopics() {return array();}
}

return 'modTvRendersGetPropertiesProcessor';