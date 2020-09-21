<?php
/*
 * This file is part of a proposed change to MODX Revolution's tv input/output option rendering in the back end.
 * Developed by Jim Graham (smg6511), Pixels+Strings, LLC
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

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
class modTvConfigsGetPropertiesProcessor extends modProcessor {

    public $propertiesKey = 'input_properties';
    public $configDirectory = 'inputproperties';
    public $onPropertiesListEvent = 'OnTVInputPropertiesList';

    public function checkPermissions() {
        return $this->modx->hasPermission('view_tv');
    }
    public function getLanguageTopics() {
        return array('tv_widget','tv_input_types');
    }

    public function initialize() {
        /* simulate controller to allow controller methods in TV Input Properties controllers */
        $this->modx->getService('smarty', 'smarty.modSmarty','');
        // $this->modx->log(modX::LOG_LEVEL_ERROR, 'Event: '.$this->modx->event->name,'', __CLASS__, __FILE__, __LINE__);
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

        $configDirectories = $this->getConfigDirectories();
        return $this->getConfigOutput($configDirectories);
    }

    /**
     * Get the properties render output when given an array of directories to search
     * @param array $configDirectories
     * @return mixed|string
     */
    public function getConfigOutput(array $configDirectories) {
        $o = '';
        foreach ($configDirectories as $configDirectory) {
            if (empty($configDirectory) || !is_dir($configDirectory)) continue;

            /* UPDATE NOTE: Remapping option to radio; remove this mapping if the field type is ultimately renamed to radio */
            $type = $this->getProperty('type');
            $type = $type == 'option' ? 'radio' : $type ;
            $configFile = $configDirectory.$type.'.php';
            // $modx->log(modX::LOG_LEVEL_ERROR, 'Looking for render file: '.$configFile,'', __CLASS__, __FILE__, __LINE__);
            if (file_exists($configFile)) {
                $modx =& $this->modx;

                /*
                    UPDATE NOTES:
                    - Adding common vars here instead of in individual render files (make sense to do?)
                    - Issue: Requests for lexicon with no entry in lexicon record results in outputting the key instead of an empty string
                */

                $params = $modx->controller->getPlaceholder('params');
                $tvId = $modx->controller->getPlaceholder('tv');
                $tvId = !empty($tvId) ? $tvId : '' ;
                $allowBlank = $params['allowBlank'] == 'true' || $params['allowBlank'] === 1 ? true : false ;
                $expandHelp = $this->getProperty('expandHelp');
                $expandHelp = $expandHelp == 'true' || $expandHelp === 1 ? true : false ;
                $helpXtype = $expandHelp ? 'label' : 'hidden' ;

                // $modx->log(modX::LOG_LEVEL_ERROR, '$params (processor): '.print_r($params,true),'', __CLASS__, __FILE__, __LINE__);
                // $modx->log(modX::LOG_LEVEL_ERROR, 'Number, allownegative_desc lexicon dump: '.print_r($modx->lexicon,true),'', __CLASS__, __FILE__, __LINE__);

                @ob_start();
                $o = include $configFile;
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
        $this->modx->controller = call_user_func_array(array($c,'getInstance'),array(&$this->modx,'TvInputPropertiesManagerController'));
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
                $settings = $tv->get($this->propertiesKey);

            }
            $this->modx->controller->setPlaceholder('tv',$tvId);
        }
        if (!isset($settings['allowBlank'])) $settings['allowBlank'] = true;
        $this->modx->controller->setPlaceholder('params',$settings);

        return $settings;
    }

    /**
     * Fire event to allow for custom directories
     * @return array
     */
    public function fireOnTVPropertiesListEvent() {
        $pluginResult = $this->modx->invokeEvent($this->onPropertiesListEvent, array(
            'context' => $this->getProperty('context'),
        ));
        if (!is_array($pluginResult) && !empty($pluginResult)) { $pluginResult = array($pluginResult); }

        return !empty($pluginResult) ? $pluginResult : array();
    }

    /**
     * Load namespace cached directories
     * @return array
     */
    public function loadNamespaceCache() {
        $cache = $this->modx->call('modNamespace', 'loadCache', array(&$this->modx));
        $cachedDirs = array();
        if (!empty($cache) && is_array($cache)) {
            foreach ($cache as $namespace) {
                $inputDir = rtrim($namespace['path'],'/').'/tv/'.$this->configDirectory.'/';
                if (is_dir($inputDir)) {
                    $cachedDirs[] = $inputDir;
                }
            }
        }
        return $cachedDirs;
    }

    /**
     * @return array
     */
    public function getConfigDirectories() {
        /* handle dynamic paths */
        $configDirectories = array(
            dirname(__FILE__).'/'.$this->getProperty('context').'/'.$this->configDirectory.'/',
        );

        $pluginResult = $this->fireOnTVPropertiesListEvent();
        $cached = $this->loadNamespaceCache();
        $configDirectories = array_merge($configDirectories, $pluginResult, $cached);

        return $configDirectories;
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

return 'modTvConfigsGetPropertiesProcessor';
