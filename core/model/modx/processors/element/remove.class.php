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
 * Abstract class for Remove Element processors. To be extended for each derivative element type.
 *
 * @abstract
 * @package modx
 * @subpackage processors.element
 */
abstract class modElementRemoveProcessor extends modObjectRemoveProcessor {
    public $staticFilePath;
    public $staticFile;

    public function cleanup() {
        $this->clearCache();
    }
    public function clearCache() {
        $this->modx->cacheManager->refresh();
    }

    public function cleanupStaticFiles() {
        if ($this->isStaticFilesAutomated()) {
            /* Remove file. */
            $count = $this->modx->getCount($this->classKey, array('static_file' => $this->staticFile));
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
        $elements = array(
            'modTemplate'    => 'templates',
            'modTemplateVar' => 'tvs',
            'modChunk'       => 'chunks',
            'modSnippet'     => 'snippets',
            'modPlugin'      => 'plugins'
        );

        if (!array_key_exists($this->classKey, $elements)) {
            return false;
        }

        return (bool) $this->modx->getOption('static_elements_automate_' . $elements[$this->classKey], null, false);
    }

    public function cleanupStaticDirectories($dirname) {
        $contents = array_diff(scandir($dirname), array('..', '.', '.DS_Store'));

        @unlink($dirname .'/.DS_Store');
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