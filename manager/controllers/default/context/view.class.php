<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modContext;
use MODX\Revolution\modManagerController;

/**
 * Loads the view context preview page.
 *
 * @package modx
 * @subpackage manager.controllers
 */
class ContextViewManagerController extends modManagerController {
    public $contextKey = '';

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('view_context');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $this->addHtml("<script>
            Ext.onReady(function() {
                MODx.load({
                   xtype: 'page-context-view'
                   ,key: MODx.request.key
                });
            });</script>");
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = []) {
        /* get context by key */
        $context= $this->modx->getObjectGraph(modContext::class, '{"ContextSettings":{}}', $scriptProperties['key']);
        if ($context == null) {
            return $this->failure($this->modx->lexicon('context_with_key_not_found',
                ['key' =>  $scriptProperties['key']]));
        }
        if (!$context->checkPolicy('view')) return $this->failure($this->modx->lexicon('permission_denied'));

        /* prepare context data for display */
        if (!$context->prepare()) {
            return $this->failure($this->modx->lexicon('context_err_load_data'));
        }

        /* assign context and display */
        $placeholders = [];
        $placeholders['context'] = $context;
        $placeholders['_ctx'] = $context->get('key');
        $this->contextKey = $context->get('key');
        return $this->modx->smarty->fetch('context/view.tpl');
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('context').': '.$this->contextKey;
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'context/view.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return ['context'];
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Contexts';
    }
}
