<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\TemplateVar\Configs;


use MODX\Revolution\modNamespace;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modTemplateVar;
use MODX\Revolution\Processors\Element\TemplateVar\Configs\Controllers\TvInputPropertiesManagerController;

/**
 * Grabs a list of input properties for a TV type
 *
 * @param string $context (optional) The context by which to grab renders from. Defaults to
 * executing context.
 * @param string $type (optional) The type of render to grab properties for.
 * Defaults to default.
 * @param integer $tv (optional) The TV to prefill property values from.
 *
 * @package MODX\Revolution\Processors\Element\TemplateVar\Configs
 */
class GetInputPropertyConfigs extends Processor {

    public $propertiesKey = 'input_properties';
    public $configDirectory = 'inputproperties';
    public $onPropertiesListEvent = 'OnTVInputPropertiesList';

    public $helpContent = [];

    public function checkPermissions() {
        return $this->modx->hasPermission('view_tv');
    }
    public function getLanguageTopics() {
        return ['tv_widget','tv_input_types'];
    }

    /**
     * Sets help content vars for field configuration
     * @param array $fieldKeys - A set of lexicon entry keys for this field
     * @param bool $expandHelp - The current system setting indicating whether help should be displayed inline
     */
    public function setHelpContent(array $fieldKeys, bool $expandHelp) {
        foreach ($fieldKeys as $k) {
            $help = $this->modx->lexicon($k);
            # avoid issue of lexicon key being returned when its entry does not exist
            $this->helpContent[$k] = $help != $k ? json_encode($help) : "''" ;
            $this->helpContent['eh_'.$k] = $expandHelp ? "''" : $this->helpContent[$k] ;
        }
    }

    public function initialize() {
        /* simulate controller to allow controller methods in TV Input Properties controllers */
        $this->modx->getService('smarty', 'smarty.modSmarty','');

        $context = $this->getProperty('context');
        if (empty($context)) {
            $this->setProperty('context',$this->modx->context->get('key'));
        }
        $this->setDefaultProperties([
            'type' => 'default',
        ]);
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

            # Remapping option to radio; remove this mapping if the field type is ultimately renamed to radio
            $type = $this->getProperty('type');
            $type = $type == 'option' ? 'radio' : $type ;
            $configFile = $configDirectory.$type.'.php';

            if (file_exists($configFile)) {
                $modx =& $this->modx;

                $params = $modx->controller->getPlaceholder('params');
                $tvId = $modx->controller->getPlaceholder('tv');
                $tvId = !empty($tvId) ? $tvId : '' ;

                # Because this value is inserted into a JSON string, a string boolean needs to be returned
                $allowBlank = $params['allowBlank'] === 'false' || $params['allowBlank'] === 0 ? 'false' : 'true' ;

                # Adding system-wide vars here instead of in individual config files
                $expandHelp = $this->getProperty('expandHelp');
                $expandHelp = $expandHelp == 'true' || $expandHelp === 1 ? true : false ;
                $helpXtype = $expandHelp ? 'label' : 'hidden' ;

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
        $this->modx->controller = call_user_func_array([$c,'getInstance'],
            [&$this->modx,TvInputPropertiesManagerController::class]);
        return $this->modx->controller->render();
    }

    /**
     * Get default display properties for specific tv
     * @return array
     */
    public function getInputProperties() {
        $settings = [];
        $tvId = $this->getProperty('tv');
        if (!empty($tvId)) {
            /** @var modTemplateVar $tv */
            $tv = $this->modx->getObject(modTemplateVar::class,$tvId);
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
        $pluginResult = $this->modx->invokeEvent($this->onPropertiesListEvent, [
            'context' => $this->getProperty('context'),
        ]);
        if (!is_array($pluginResult) && !empty($pluginResult)) { $pluginResult = [$pluginResult]; }

        return !empty($pluginResult) ? $pluginResult : [];
    }

    /**
     * Load namespace cached directories
     * @return array
     */
    public function loadNamespaceCache() {
        $cache = $this->modx->call(modNamespace::class, 'loadCache', [&$this->modx]);
        $cachedDirs = [];
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
        $configDirectories = [
            dirname(__FILE__).'/'.$this->getProperty('context').'/'.$this->configDirectory.'/',
        ];

        $pluginResult = $this->fireOnTVPropertiesListEvent();
        $cached = $this->loadNamespaceCache();
        $configDirectories = array_merge($configDirectories, $pluginResult, $cached);

        return $configDirectories;
    }
}
