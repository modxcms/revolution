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
 * Renames a file
 *
 * @param string $file The file to rename
 * @param string $newname The new name for the file
 *
 * @package modx
 * @subpackage processors.browser
 */
class modBrowserFileRenameProcessor extends modProcessor {
    /** @var modMediaSource|modFileMediaSource $source */
    public $source;
    public function checkPermissions() {
        return $this->modx->hasPermission('file_update');
    }
    public function getLanguageTopics() {
        return array('file');
    }
    public function process() {
        if (!$this->validate()) {
            return $this->failure();
        }

        $loaded = $this->getSource();
        if (!($this->source instanceof modMediaSource)) {
            return $loaded;
        }
        if (!$this->source->checkPolicy('save')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }

        $oldFile = $this->getProperty('path');
        $pathinfo = pathinfo($oldFile);
        if ($pathinfo['dirname'].DIRECTORY_SEPARATOR.$pathinfo['basename'] != $oldFile) {
            $this->modx->log (modX::LOG_LEVEL_ERROR, 'Could not prepare the filepath ' . $oldFile . '. Please set a valid UTF8 capable locale in the MODX system setting "locale".');
        }
        $directory = preg_replace('/[\.]{2,}/', '', htmlspecialchars($pathinfo['dirname']));
        $name = htmlspecialchars($pathinfo['basename']);
        $oldFile = $directory.DIRECTORY_SEPARATOR.$name;

        $newFile = $this->getProperty('name');
        $pathinfo = pathinfo($newFile);
        if ($pathinfo['basename'] != $newFile) {
            $this->modx->log (modX::LOG_LEVEL_ERROR, 'Could not prepare the filepath ' . $newFile . '. Please set a valid UTF8 capable locale in the MODX system setting "locale".');
        }
        $directory = preg_replace('/[\.]{2,}/', '', htmlspecialchars($pathinfo['dirname']));
        $name = htmlspecialchars($pathinfo['basename']);
        $newFile = $directory.DIRECTORY_SEPARATOR.$name;
        $success = $this->source->renameObject($oldFile, $newFile);

        if (empty($success)) {
            $msg = '';
            $errors = $this->source->getErrors();
            foreach ($errors as $k => $msg) {
                $this->addFieldError($k,$msg);
            }
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

    /**
     * Validate form
     * @return boolean
     */
    public function validate() {
        $dir = $this->getProperty('path');
        if (empty($dir)) $this->addFieldError('path',$this->modx->lexicon('file_err_ns'));
        $name = $this->getProperty('name');
        if (empty($name)) $this->addFieldError('name',$this->modx->lexicon('name_err_ns'));

        return !$this->hasErrors();
    }
}
return 'modBrowserFileRenameProcessor';

