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
use MODX\Revolution\modSystemEvent;

/**
 * Loads the view context preview page.
 *
 * @package modx
 * @subpackage manager.controllers
 */
class ContextUpdateManagerController extends modManagerController {
    /**
     * The key of the current context
     * @var string $contextKey
     */
    public $contextKey;
    /**
     * The return value from the OnContextFormRender event
     * @var string $onContextFormRender
     */
    public $onContextFormRender;
    /**
     * The context to update.
     * @var modContext $context
     */
    public $context;

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('edit_context');
    }

    /**
     * Get the context to update
     * @return void
     */
    public function initialize() {
        $this->context= $this->modx->getObjectGraph(modContext::class, '{"ContextSettings":{}}', ['key' => $this->scriptProperties['key']]);
        if ($this->context) {
            $this->contextKey = $this->context->get('key');
        }
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addHtml("<script>
            // <![CDATA[
            MODx.onContextFormRender = '".$this->onContextFormRender."';
            MODx.ctx = '".$this->contextKey."';
            Ext.onReady(function() {
                MODx.add('modx-page-context-update');
            });
            // ]]>
            </script>");
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.access.context.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/core/modx.grid.settings.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/system/modx.grid.context.settings.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/system/modx.panel.context.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/context/update.js');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = []) {
        if (empty($this->context)) {
            return $this->failure(sprintf($this->modx->lexicon('context_with_key_not_found'), htmlentities($this->scriptProperties['key'], ENT_QUOTES, 'UTF-8')));
        }
        if (!$this->context->checkPolicy(['view' => true, 'save' => true])) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        /* prepare context data for display */
        if (!$this->context->prepare()) {
            return $this->failure($this->modx->lexicon('context_err_load_data'));
        }

        /* invoke OnContextFormPrerender event */
        $this->setPlaceholder('OnContextFormPrerender',$this->onPreRender());

        /* invoke OnContextFormRender event */
        $this->setPlaceholder('OnContextFormRender',$this->onRender());

        /*  assign context to smarty and display */
        $this->setPlaceholder('context',$this->context);
        $this->setPlaceholder('_ctx',$this->context->get('key'));

        return null;
    }

    /**
     * @return mixed
     */
    public function onPreRender() {
        $onContextFormPrerender = $this->modx->invokeEvent('OnContextFormPrerender', [
            'key' => $this->context->get('key'),
            'context' => &$this->context,
            'mode' => modSystemEvent::MODE_UPD,
        ]);
        if (is_array($onContextFormPrerender)) $onContextFormPrerender = implode('',$onContextFormPrerender);
        return $onContextFormPrerender;
    }

    /**
     * @return mixed
     */
    public function onRender() {
        $this->onContextFormRender = $this->modx->invokeEvent('OnContextFormRender', [
            'key' => $this->context->get('key'),
            'context' => &$this->context,
            'mode' => modSystemEvent::MODE_UPD,
        ]);
        if (is_array($this->onContextFormRender)) $this->onContextFormRender = implode('',$this->onContextFormRender);
        $this->onContextFormRender = str_replace(['"',"\n","\r"], ['\"','',''],$this->onContextFormRender);
        return $this->onContextFormRender;
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
        return '';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return ['context','setting','access','policy','user'];
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Contexts';
    }
}
