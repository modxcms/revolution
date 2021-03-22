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
 * Loads the edit file page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SystemFileEditManagerController extends modManagerController {
    /** @var string The basename of the file */
    public $filename = '';
    /** @var array An array of data about the file */
    public $fileRecord = array();
    /** @var bool A boolean stating whether or not this file can be saved */
    public $canSave = false;

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('file_view');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/sections/system/file/edit.js');
        $this->addHtml('<script>Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-file-edit"
                ,file: "'.$this->filename.'"
                ,record: '.$this->modx->toJSON($this->fileRecord).'
                ,canSave: '.($this->canSave ? 1 : 0).'
            });
        });</script>');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = array()) {
        $placeholders = array();
        $this->modx->lexicon->load('file');

        if (empty($_GET['file'])) return $this->failure($this->modx->lexicon('file_err_nf'));

        $this->loadWorkingContext();
        /* format filename */
        $this->filename = preg_replace('#([\\\\]+|/{2,})#', '/',$scriptProperties['file']);
        $this->filename = htmlspecialchars(strip_tags($this->filename));

        $source = $this->getSource();
        if (!$source || !$source->initialize()) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }

        $this->fileRecord = $source->getObjectContents($this->filename);
        $this->fileRecord['source'] = $source->get('id');

        if (empty($this->fileRecord)) {
            $errors = $source->getErrors();
            $error = '';
            foreach ($errors as $k => $msg) {
                $error .= $msg;
            }
            return $this->failure($error);
        }
        $this->canSave = $this->fileRecord['is_writable'] ? true : false;

        $placeholders['fa'] = $this->fileRecord;
        $placeholders['OnFileEditFormPrerender'] = $this->fireEvents();

        $this->fileRecord['basename'] = htmlspecialchars($this->fileRecord['basename']);
        $this->fileRecord['name'] = htmlspecialchars($this->fileRecord['name']);
        $this->fileRecord['path'] = htmlspecialchars($this->fileRecord['path']);

        return $placeholders;
    }

    /**
     * Get the active source
     * @return modMediaSource|bool
     */
    public function getSource() {
        /** @var modMediaSource|modFileMediaSource $source */
        $this->modx->loadClass('sources.modMediaSource');
        $source = $this->modx->getOption('source',$this->scriptProperties,false);
        if (!empty($source)) {
            $source = $this->modx->getObject('source.modMediaSource',$source);
        }
        if (empty($source)) {
            $source = modMediaSource::getDefaultSource($this->modx);
        }
        if (!$source->getWorkingContext()) {
            return false;
        }
        $source->setRequestProperties($this->scriptProperties);
        return $source;
    }

    /**
     * Invoke OnFileEditFormPrerender event
     * @return string
     */
    public function fireEvents() {
        $onFileEditFormPrerender = $this->modx->invokeEvent('OnFileEditFormPrerender',array(
            'mode' => modSystemEvent::MODE_UPD,
            'file' => $this->filename,
            'fa' => &$this->fileRecord,
        ));
        if (is_array($onFileEditFormPrerender)) $onFileEditFormPrerender = implode('',$onFileEditFormPrerender);
        return $onFileEditFormPrerender;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('file_edit').': '.basename($this->filename);
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return '';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('file');
    }
}
