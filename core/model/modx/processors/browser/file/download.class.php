<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

class modBrowserFileDownloadProcessor extends modProcessor {
    /** @var modMediaSource|modFileMediaSource $source */
    public $source;
    public function checkPermissions() {
        return $this->modx->hasPermission('file_view');
    }
    public function getLanguageTopics() {
        return array('file');
    }

    public function process() {
        $source = $this->getSource();
        if ($source !== true) {
            return $source;
        }
        if (!$this->source->checkPolicy('view')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }

        if ($this->getProperty('download',false)) {
            return $this->download();
        } else {
            return $this->getObjectUrl();
        }
    }

    public function getObjectUrl() {
        /* format filename */
        $file = rawurldecode($this->getProperty('file',''));
        $file = preg_replace('/[\.]{2,}/', '', htmlspecialchars($file));
        $url = $this->source->getObjectUrl($file);
        return $this->success('',array('url' => $url));
    }

    public function download() {
        $fileobj = $this->source->fileHandler->make($this->source->getBasePath() . $this->getProperty('file'));

        $fileobj->download();
    }

    /**
     * @return boolean|string
     */
    public function getSource() {
        $source = $this->getProperty('source',1);
        /** @var modMediaSource $source */
        $this->modx->loadClass('sources.modMediaSource');
        $this->source = modMediaSource::getDefaultSource($this->modx,$source);
        if (!$this->source->getWorkingContext()) {
            return $this->modx->lexicon('permission_denied');
        }
        $this->source->setRequestProperties($this->getProperties());
        return $this->source->initialize();
    }
}
return 'modBrowserFileDownloadProcessor';
