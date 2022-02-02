<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modManagerController;
use MODX\Revolution\modSystemEvent;
use MODX\Revolution\Sources\modFileMediaSource;
use MODX\Revolution\Sources\modMediaSource;

/**
 * Loads the edit file page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SystemFileEditManagerController extends modManagerController
{
    /** @var string The basename of the file */
    public $filename = '';
    /** @var array An array of data about the file */
    public $fileRecord = [];
    /** @var bool A boolean stating whether or not this file can be saved */
    public $canSave = false;


    /**
     * Check for any permissions or requirements to load page
     *
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('file_view');
    }


    /**
     * Specify the language topics to load
     *
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['file', 'source'];
    }


    /**
     * Register custom CSS/JS for the page
     *
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addJavascript($this->modx->getOption('manager_url') . 'assets/modext/sections/system/file/edit.js');
        $data = json_encode([
            'xtype' => 'modx-page-file-edit',
            'file' => $this->filename,
            'record' => $this->fileRecord,
            'canSave' => (int)$this->canSave,
        ], JSON_INVALID_UTF8_SUBSTITUTE);
        $this->addHtml('<script>Ext.onReady(function() {MODx.load(' . $data . ');});</script>');
    }


    /**
     * Custom logic code here for setting placeholders, etc
     *
     * @param array $scriptProperties
     *
     * @return mixed
     */
    public function process(array $scriptProperties = [])
    {
        $placeholders = [];
        if (empty($_GET['file'])) {
            $this->failure($this->modx->lexicon('file_err_nf'));

            return false;
        }

        $this->loadWorkingContext();
        $this->filename = rawurldecode($scriptProperties['file']);
        if (!$source = $this->getSource()) {
            return false;
        }

        if ($this->fileRecord = $source->getObjectContents($this->filename)) {
            $this->fileRecord['source'] = $source->get('id');
        }
        if (empty($this->fileRecord)) {
            $errors = $source->getErrors();
            $error = '';
            foreach ($errors as $k => $msg) {
                $error .= $msg;
            }
            $this->failure($error);

            return false;
        }
        $this->canSave = true;

        $placeholders['fa'] = $this->fileRecord;
        $placeholders['OnFileEditFormPrerender'] = $this->fireEvents();

        return $placeholders;
    }


    /**
     * Get the active source
     *
     * @return modMediaSource|bool
     */
    public function getSource()
    {
        /** @var modMediaSource|modFileMediaSource $source */
        $source = $this->modx->getOption('source', $this->scriptProperties, false);
        if (!empty($source)) {
            $source = $this->modx->getObject(modMediaSource::class, $source);
        }
        if (empty($source)) {
            $source = modMediaSource::getDefaultSource($this->modx);
        }
        if (!$source->getWorkingContext()) {
            $this->failure($this->modx->lexicon('permission_denied'));

            return false;
        }
        $source->setRequestProperties($this->scriptProperties);
        if (!$source->initialize()) {
            $this->failure($this->modx->lexicon('source_err_init', ['source' => $source->get('name')]));

            return false;
        }

        return $source;
    }


    /**
     * Invoke OnFileEditFormPrerender event
     *
     * @return string
     */
    public function fireEvents()
    {
        $onFileEditFormPrerender = $this->modx->invokeEvent('OnFileEditFormPrerender', [
            'mode' => modSystemEvent::MODE_UPD,
            'file' => $this->filename,
            'fa' => &$this->fileRecord,
        ]);
        if (is_array($onFileEditFormPrerender)) {
            $onFileEditFormPrerender = implode('', $onFileEditFormPrerender);
        }

        return $onFileEditFormPrerender;
    }


    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('file_edit') . ': ' . basename($this->filename);
    }


    /**
     * Return the location of the template file
     *
     * @return string
     */
    public function getTemplateFile()
    {
        return '';
    }
}
