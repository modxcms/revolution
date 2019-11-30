<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Import;

use MODX\Revolution\Import\modStaticImport;
use MODX\Revolution\modContext;
use MODX\Revolution\Processors\ModelProcessor;
use MODX\Revolution\modResource;
use MODX\Revolution\modStaticResource;

/**
 * @package MODX\Revolution\Processors\System\Import
 */
class Index extends ModelProcessor
{
    public $permission = 'import_static';
    public $languageTopics = ['import'];
    public $importTime;
    public $allowedFiles = [];
    /** @var modStaticImport */
    public $import;
    public $filesFound = 0;
    public $classKey = modStaticResource::class;
    public $parent;

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->importTime = microtime(true);
        $this->import = $this->modx->getService('import', modStaticImport::class, '', []);
        $this->setAllowedFiles();
        $this->parent = $this->getProperty('import_parent', 0);
        $class = $this->getProperty('import_resource_class');
        if (!empty($class)) {
            $this->classKey = $this->modx->loadClass($class);
        }

        return true;
    }

    public function setAllowedFiles()
    {
        $import_extensions = trim($this->getProperty('import_allowed_extensions', ''), ' ,');
        $this->allowedFiles = empty($import_extensions) ? $this->allowedFiles : array_merge($this->allowedFiles,
            explode(',', $import_extensions));
    }

    /**
     * @return array|mixed|string
     * @throws \xPDO\xPDOException
     */
    public function process()
    {
        $context = $this->getContext();
        $paths = $this->getPaths($context);
        $this->importFiles($paths, $context);

        return $this->cleanup();
    }

    /**
     * @return mixed
     */
    public function getContext()
    {
        // is import_context being used anywhere? It isn't in the manager form...
        $context = $this->getProperty('import_context', 'web');
        $parentRes = $this->modx->getObject(modResource::class, $this->parent);
        if ($parentRes) {
            $context_key = $parentRes->get('context_key');
            $context = $context_key ?: $context;
        }

        return $context;
    }

    /**
     * @param $context
     * @return array
     */
    public function getPaths($context)
    {
        $file_path = $this->getProperty('import_base_path');
        $base_file_path = '';
        if (empty($file_path)) {
            $file_path = $this->modx->getOption('core_path') . 'import/';
            /** @var modContext $contextObj */
            $contextObj = $this->modx->getObject(modContext::class, $context);
            if ($contextObj) {
                $contextObj->prepare();
                $context_path = $contextObj->getOption('resource_static_path');
                $path = $this->modx->getOption('resource_static_path');
                if (!empty($context_path)) {
                    $file_path = $context_path;
                } elseif (!empty($path)) {
                    $file_path = $path;
                }
            }
            $base_file_path = $file_path;
        }

        return [
            'file' => $file_path,
            'base_file' => $base_file_path,
        ];
    }

    /**
     * @param $paths
     * @param $context
     * @throws \xPDO\xPDOException
     */
    public function importFiles($paths, $context)
    {
        $elements = $this->getElements();
        $files = $this->import->getFiles($this->filesFound, $paths['file']);
        @ini_set('max_execution_time', 0);
        if ($this->filesFound > 0) {
            $this->import->importFiles($this->allowedFiles, $this->parent, $paths['file'], $files, $context,
                $this->classKey, $paths['base_file'], $elements);
        }

    }

    /**
     * Prepare associations between resource fields and html selectors
     * @return array
     * @throws \xPDO\xPDOException
     */
    public function getElements()
    {
        $elements = $this->getProperty('import_element', false);
        if ($elements && strpos($elements, '{') !== false) {
            $elements = $this->modx->fromJSON($elements);
        } else {
            $elements = ['content' => $elements];
        }

        // default value
        if (empty($elements) || !is_array($elements)) {
            $elements = ['content' => '$body'];
        }
        return $elements;
    }

    public function cleanup()
    {
        $this->importTime = microtime(true) - $this->importTime;
        $results = sprintf($this->modx->lexicon('import_files_found'), $this->filesFound) . '<br />' . implode('<br />',
                $this->import->results) . sprintf('<br />' . $this->modx->lexicon('import_site_time'),
                round($this->importTime, 3));

        return $this->success($results);
    }
}
