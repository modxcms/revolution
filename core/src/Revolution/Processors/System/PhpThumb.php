<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System;

use MODX\Revolution\File\modFileHandler;
use MODX\Revolution\modPhpThumb;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modX;
use MODX\Revolution\Sources\modFileMediaSource;
use MODX\Revolution\Sources\modMediaSource;

/**
 * Generate a thumbnail
 * @package MODX\Revolution\Processors\System
 */
class PhpThumb extends Processor
{
    /** @var modPhpThumb $phpThumb */
    public $phpThumb;

    /** @var modMediaSource|modFileMediaSource $source */
    public $source;

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->setDefaultProperties([
            'wctx' => $this->modx->context->get('key'),
            'src' => '',
            'source' => 1,
        ]);
        $this->modx->getService('fileHandler', modFileHandler::class, '', [
            'context' => $this->getProperty('wctx'),
        ]);
        $this->unsetProperty('wctx');
        $this->unsetProperty('version');
        error_reporting(E_ALL);
        return true;
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        $src = rawurldecode($this->getProperty('src'));
        if (empty($src)) {
            return $this->failure();
        }
        $this->unsetProperty('src');

        $this->getSource($this->getProperty('source'));
        if ($this->source === null) {
            $this->failure($this->modx->lexicon('source_err_nf'));
        }
        $this->unsetProperty('source');

        $src = $this->source->prepareSrcForThumb($src);
        if (empty($src)) {
            return '';
        }

        if (strtolower(pathinfo($src, PATHINFO_EXTENSION)) === 'svg') {
            /* Skip thumbnail generation for svg and output the file directly */
            header('Content-Type: image/svg+xml');
            echo file_get_contents($src);
            return '';
        }

        $this->unsetProperty('t');

        $this->loadPhpThumb();
        /* set source and generate thumbnail */
        $this->phpThumb->set($src);

        /* check to see if there's a cached file of this already */
        if ($this->phpThumb->checkForCachedFile()) {
            $this->phpThumb->loadCache();
            return '';
        }

        /* generate thumbnail */
        $this->phpThumb->generate();

        /* cache the thumbnail and output */
        $this->phpThumb->cache();
        $this->phpThumb->output();
        return '';
    }

    /**
     * Get the source to load the paths from
     * @param int $sourceId
     * @return modMediaSource|modFileMediaSource|boolean
     */
    public function getSource($sourceId)
    {
        /** @var modMediaSource|modFileMediaSource $source */
        $this->source = modMediaSource::getDefaultSource($this->modx, $sourceId, false);
        if ($this->source === null) {
            return false;
        }

        if (!$this->source->getWorkingContext()) {
            return false;
        }
        $this->source->setRequestProperties($this->getProperties());
        $this->source->initialize();

        return $this->source;
    }

    /**
     * Attempt to load modPhpThumb
     * @return bool|modPhpThumb
     */
    public function loadPhpThumb()
    {
        if (!$this->modx->getService('phpthumb', modPhpThumb::class)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not load modPhpThumb class.');
            return false;
        }
        $this->phpThumb = new modPhpThumb($this->modx, $this->getProperties());
        /* do initial setup */
        $this->phpThumb->initialize();

        return $this->phpThumb;
    }
}
