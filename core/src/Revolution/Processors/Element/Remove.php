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
        if ($this->object->isStatic() && $this->object->isStaticFilesAutomated()) {
            $count = $this->modx->getCount($this->classKey, ['static_file' => $this->staticFile]);
            if ($this->staticFilePath && $count === 0) {
                @unlink($this->staticFilePath);
            }

            // Check if parent directory is empty, if so remove parent directory.
            $pathinfo = pathinfo($this->staticFilePath);

            if (!empty($pathinfo['dirname'])) {
                $this->object->cleanupStaticFileDirectories($pathinfo['dirname']);
            }
        }
    }
}
