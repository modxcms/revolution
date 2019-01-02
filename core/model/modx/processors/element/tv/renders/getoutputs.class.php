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
 * Grabs a list of output renders for the tv.
 *
 * @param string $context (optional) The context by which to grab renders from. Defaults to web.
 *
 * @package modx
 * @subpackage processors.element.tv.renders
 */

class modElementTVRendersGetOutputsProcessor extends modProcessor {

    /**
     * Check permissions to view TV
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('view_tv');
    }

    /**
     * Load Language Topics for this processor.
     * @return array
     */
    public function getLanguageTopics() {
        return array('tv_widget');
    }

    /**
     * Fire event to allow for custom directories
     * @param $context
     * @return array
     */
    public function fireOnTVOutputRenderListEvent($context) {
        $pluginResult = $this->modx->invokeEvent('OnTVOutputRenderList',array(
            'context' => $context,
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
                $inputDir = rtrim($namespace['path'],'/').'/tv/output/';
                if (is_dir($inputDir)) {
                    $cachedDirs[] = $inputDir;
                }
            }
        }
        return $cachedDirs;
    }

    /**
     * Get directories where to find TV output types
     * @return array
     */
    public function getRenderDirectories() {
        $context = $this->getProperty('context', 'web');

        $renderDirectories = array(
            dirname(__FILE__).'/'.$context.'/output/',
        );

        $pluginResult = $this->fireOnTVOutputRenderListEvent($context);
        $cached = $this->loadNamespaceCache();
        $renderDirectories = array_merge($renderDirectories, $pluginResult, $cached);

        return $renderDirectories;
    }

    /**
     * Find TV output types
     * @param array $data
     * @return array
     */
    public function iterate(array $data) {
        $types = array();
        foreach ($data as $renderDirectory) {
            if (empty($renderDirectory) || !is_dir($renderDirectory)) continue;
            try {
                $dirIterator = new DirectoryIterator($renderDirectory);
                foreach ($dirIterator as $file) {
                    if (!$file->isReadable() || !$file->isFile()) continue;
                    $type = str_replace(array('.php', '.class', '.class.php'), '', $file->getFilename());
                    $types[$type] = array(
                        'name' => $this->modx->lexicon($type),
                        'value' => $type,
                    );
                }
            } catch (UnexpectedValueException $e) {}
        }

        return $types;
    }

    /**
     * {@inheritdoc}
     * @return mixed|string
     */
    public function process() {
        $renderDirectories = $this->getRenderDirectories();
        $types = $this->iterate($renderDirectories);
        return $this->cleanup($types);
    }

    /**
     * Prepare list of types for response
     * @param array $types
     * @return string
     */
    public function cleanup(array $types) {
        ksort($types);
        $types = array_values($types);
        return $this->outputArray($types);
    }
}

return 'modElementTVRendersGetOutputsProcessor';
