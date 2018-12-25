<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * modElementTvRendersGetInputsProcessor
 *
 * @package modx
 */
require_once MODX_CORE_PATH.'model/modx/modmanagercontroller.class.php';
/**
 * Grabs a list of inputs for a TV.
 *
 * @param string $context (optional) The context by which to grab renders from. Defaults to
 * executing context.
 *
 * @package modx
 * @subpackage processors.element.tv.renders
 */
class modElementTvRendersGetInputsProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('view_tv');
    }
    public function getLanguageTopics() {
        return array('tv_widget','tv_input_types');
    }

    public function initialize() {
        /* simulate controller to allow controller methods in TV Input Properties controllers */
        $this->modx->getService('smarty', 'smarty.modSmarty','');
        return true;
    }

    public function process() {
        $context = $this->getProperty('context',$this->modx->context->get('key'));

        /* simulate controller with the faux class above */
        $c = new TvInputManagerController($this->modx);
        $this->modx->controller = call_user_func_array(array($c,'getInstance'),array(&$this->modx,'TvInputManagerController'));
        $this->modx->controller->render();

        $renderDirectories = array(
            dirname(__FILE__).'/'.$context.'/input/',
        );

        /* allow for custom directories */
        $pluginResult = $this->modx->invokeEvent('OnTVInputRenderList',array(
            'context' => $context,
        ));
        if (!is_array($pluginResult) && !empty($pluginResult)) { $pluginResult = array($pluginResult); }
        if (!empty($pluginResult)) {
            $renderDirectories = array_merge($renderDirectories,$pluginResult);
        }

        /* load namespace caches */
        $cache = $this->modx->call('modNamespace','loadCache',array(&$this->modx));
        if (!empty($cache) && is_array($cache)) {
            foreach ($cache as $namespace) {
                $inputDir = rtrim($namespace['path'],'/').'/tv/input/';
                if (is_dir($inputDir)) {
                    $renderDirectories[] = $inputDir;
                }
            }
        }

        /* search directories */
        $types = array();
        foreach ($renderDirectories as $renderDirectory) {
            if (empty($renderDirectory) || !is_dir($renderDirectory)) continue;
            try {
                $dirIterator = new DirectoryIterator($renderDirectory);
                foreach ($dirIterator as $file) {
                    if (!$file->isReadable() || !$file->isFile()) continue;
                    $type = str_replace(array('.php','.class','.class.php'),'',$file->getFilename());
                    $types[$type] = array(
                        'name' => $this->modx->lexicon($type),
                        'value' => $type,
                    );
                }
            } catch (UnexpectedValueException $e) {}
        }

        /* sort types */
        asort($types);
        $otypes = array();
        foreach ($types as $type) {
            $otypes[] = $type;
        }

        return $this->outputArray($otypes);
    }
}

/**
 * Simulate the TV manager controller to get TV input renders
 *
 * @package modx
 */
class TvInputManagerController extends modManagerController {
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

return 'modElementTvRendersGetInputsProcessor';
