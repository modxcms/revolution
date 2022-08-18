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
class SystemFileCreateManagerController extends modManagerController
{
    /** @var string The directory to create in */
    public $directory = '';
    /** @var array An array of data about the file */
    public $fileRecord = [];
    /** @var modMediaSource $source */
    public $source = null;


    /**
     * Check for any permissions or requirements to load page
     *
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('file_create');
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
        $this->addJavascript($this->modx->getOption('manager_url') . 'assets/modext/sections/system/file/create.js');
        $data = json_encode([
            'xtype' => 'modx-page-file-create',
            'record' => $this->fileRecord,
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
        if (!$source = $this->getSource()) {
            return false;
        }

        $directory = !empty($scriptProperties['directory']) ? $scriptProperties['directory'] : '';

        $this->fileRecord = [
            'directory' => strip_tags(preg_replace('#^(\.{2}|/)+#u', '', $directory)),
            'source' => $source->get('id'),
        ];

        $this->loadWorkingContext();

        $placeholders['OnFileCreateFormPrerender'] = $this->fireEvents();

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
        $OnFileCreateFormPrerender = $this->modx->invokeEvent('OnFileCreateFormPrerender', [
            'mode' => modSystemEvent::MODE_NEW,
            'directory' => $this->directory,
        ]);
        if (is_array($OnFileCreateFormPrerender)) {
            $OnFileCreateFormPrerender = implode('', $OnFileCreateFormPrerender);
        }

        return $OnFileCreateFormPrerender;
    }


    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('file_create');
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
