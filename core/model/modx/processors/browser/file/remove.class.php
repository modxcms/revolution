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
 * Removes a file.
 *
 * @param string $file The name of the file.
 * @param boolean $prependPath If true, will prepend the rb_base_dir to the file
 * name.
 *
 * @package modx
 * @subpackage processors.browser.file
 */
class modBrowserFileRemoveProcessor extends modProcessor {
    /** @var modMediaSource|modFileMediaSource $source */
    public $source;
    public function checkPermissions() {
        return $this->modx->hasPermission('file_remove');
    }
    public function getLanguageTopics() {
        return array('file');
    }

    public function process() {
        $file = $this->getProperty('file');
        if (empty($file)) {
            return $this->modx->error->failure($this->modx->lexicon('file_err_ns'));
        }
        $file = preg_replace('/[\.]{2,}/', '', $file);

        $loaded = $this->getSource();
        if (!($this->source instanceof modMediaSource)) {
            return $loaded;
        }
        if (!$this->source->checkPolicy('remove')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $success = $this->source->removeObject($file);

        if (empty($success)) {
            $errors = $this->source->getErrors();
            $msg = implode("\n", $errors);

            return $this->failure($msg);
        }

        return $this->success();
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
return 'modBrowserFileRemoveProcessor';
