<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element;


use MODX\Revolution\modChunk;
use MODX\Revolution\Processors\Model\RemoveProcessor;
use MODX\Revolution\modPlugin;
use MODX\Revolution\modSnippet;
use MODX\Revolution\modTemplate;
use MODX\Revolution\modTemplateVar;

/**
 * Abstract class for Remove Element processors. To be extended for each derivative element type.
 *
 * @abstract
 *
 * @package MODX\Revolution\Processors\Element
 */
abstract class Remove extends RemoveProcessor
{
    public $staticFilePath;
    public $staticFile;

    public function cleanup()
    {
        $this->clearCache();
    }

    public function clearCache()
    {
        $this->modx->cacheManager->refresh();
    }

    public function cleanupStaticFiles()
    {
        if ($this->isStaticFilesAutomated()) {
            /* Remove file. */
            $count = $this->modx->getCount($this->classKey, ['static_file' => $this->staticFile]);
            if ($this->staticFilePath && $count === 0) {
                @unlink($this->staticFilePath);
            }

            /* Check if parent directory is empty, if so remove parent directory. */
            $pathinfo = pathinfo($this->staticFilePath);

            if (!empty($pathinfo['dirname'])) {
                $this->cleanupStaticDirectories($pathinfo['dirname']);
            }
        }
    }

    /**
     * Determine if static files should be automated for current element class.
     *
     * @return bool
     */
    protected function isStaticFilesAutomated()
    {
        $elements = [
            modTemplate::class => 'templates',
            modTemplateVar::class => 'tvs',
            modChunk::class => 'chunks',
            modSnippet::class => 'snippets',
            modPlugin::class => 'plugins',
        ];

        if (!array_key_exists($this->classKey, $elements)) {
            return false;
        }

        return (bool)$this->modx->getOption('static_elements_automate_' . $elements[$this->classKey], null, false);
    }

    public function cleanupStaticDirectories($dirname)
    {
        $contents = array_diff(scandir($dirname), ['..', '.', '.DS_Store']);

        @unlink($dirname . '/.DS_Store');
        if (count($contents) === 0) {
            if (is_dir($dirname)) {
                if (rmdir($dirname)) {
                    /* Check if parent directory is also empty. */
                    $this->cleanupStaticDirectories(dirname($dirname));
                }
            }
        }
    }
}
