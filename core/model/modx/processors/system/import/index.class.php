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
 * @package modx
 * @subpackage processors.system.import
 */

class modSystemImportIndexProcessor extends modObjectProcessor {
    public $permission = 'import_static';
    public $languageTopics = array('import');
    public $importTime;
    public $allowedFiles = array();
    /** @var modStaticImport*/
    public $import;
    public $filesFound = 0;
    public $classKey = 'modStaticResource';
    public $parent;

    public function initialize() {
        $this->importTime = microtime(true);
        $this->import = $this->modx->getService('import', 'import.modStaticImport', '', array ());
        $this->setAllowedFiles();
        $this->parent = $this->getProperty('import_parent', 0);
        $class = $this->getProperty('import_resource_class');
        if (!empty($class)) {
            $this->classKey = $this->modx->loadClass($class);
        }

        return true;
    }

    public function setAllowedFiles() {
        $import_exts = trim($this->getProperty('import_allowed_extensions', ''), ' ,');
        $this->allowedFiles = empty($import_exts) ? $this->allowedFiles :
            array_merge($this->allowedFiles, explode(',', $import_exts));
    }

    public function getContext() {
        // is import_context being used anywhere? It isn't in the manager form...
        $context = $this->getProperty('import_context', 'web');
        $parentRes = $this->modx->getObject('modResource', $this->parent);
        if ($parentRes) {
            $context_key = $parentRes->get('context_key');
            $context = $context_key ? $context_key : $context;
        }

        return $context;
    }

    public function getPaths($context) {
        $file_path = $this->getProperty('import_base_path');
        $base_file_path = '';
        if (empty($file_path)) {
            $file_path = $this->modx->getOption('core_path') . 'import/';
            /** @var modContext $contextObj */
            $contextObj = $this->modx->getObject('modContext', $context);
            if ($contextObj) {
                $contextObj->prepare();
                $crsp = $contextObj->getOption('resource_static_path');
                $rsp = $this->modx->getOption('resource_static_path');
                if (!empty($crsp)) {
                    $file_path = $crsp;
                } elseif (!empty($rsp)) {
                    $file_path = $rsp;
                }
            }
            $base_file_path = $file_path;
        }

        return array(
            'file' => $file_path,
            'base_file' => $base_file_path,
        );
    }

    /**
     * Prepare associations between resource fields and html selectors
     * @return array
     */
    public function getElements() {
        $elements = $this->getProperty('import_element', false);
        if ($elements && strpos($elements, '{') !== false) {
            $elements = $this->modx->fromJSON($elements);
        } else {
            $elements = array('content' => $elements);
        }

        // default value
        if (empty($elements) || !is_array($elements)) {
            $elements = array('content' => '$body');
        }
        return $elements;
    }

    public function importFiles($paths, $context) {
        $elements = $this->getElements();
        $files = $this->import->getFiles($this->filesFound, $paths['file']);
        @ini_set('max_execution_time', 0);
        if ($this->filesFound > 0) {
            $this->import->importFiles($this->allowedFiles, $this->parent, $paths['file'], $files, $context,
                $this->classKey, $paths['base_file'], $elements);
        }

    }

    public function process() {
        $context = $this->getContext();
        $paths = $this->getPaths($context);
        $this->importFiles($paths, $context);

        return $this->cleanup();
    }

    public function cleanup() {
        $this->importTime = microtime(true) - $this->importTime;
        $results = sprintf($this->modx->lexicon('import_files_found'), $this->filesFound) . '<br />' .
            implode('<br />', $this->import->results) .
            sprintf("<br />" . $this->modx->lexicon('import_site_time'), round($this->importTime, 3));
        return $this->success($results);
    }
}

return 'modSystemImportIndexProcessor';
