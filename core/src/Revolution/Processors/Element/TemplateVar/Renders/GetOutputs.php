<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\TemplateVar\Renders;


use DirectoryIterator;
use MODX\Revolution\modNamespace;
use MODX\Revolution\Processors\Processor;
use UnexpectedValueException;

/**
 * Grabs a list of output renders for the tv.
 *
 * @param string $context (optional) The context by which to grab renders from. Defaults to web.
 *
 * @package MODX\Revolution\Processors\Element\TemplateVar\Renders
 */
class GetOutputs extends Processor {

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
        return ['tv_widget'];
    }

    /**
     * Fire event to allow for custom directories
     * @param $context
     * @return array
     */
    public function fireOnTVOutputRenderListEvent($context) {
        $pluginResult = $this->modx->invokeEvent('OnTVOutputRenderList', [
            'context' => $context,
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

        $renderDirectories = [
            dirname(__FILE__).'/'.$context.'/output/',
        ];

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
        $types = [];
        foreach ($data as $renderDirectory) {
            if (empty($renderDirectory) || !is_dir($renderDirectory)) continue;
            try {
                $dirIterator = new DirectoryIterator($renderDirectory);
                foreach ($dirIterator as $file) {
                    if (!$file->isReadable() || !$file->isFile()) continue;
                    $type = str_replace(['.php', '.class', '.class.php'], '', $file->getFilename());
                    $types[$type] = [
                        'name' => $this->modx->lexicon($type),
                        'value' => $type,
                    ];
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
