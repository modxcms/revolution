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

/**
 * Class ResourceIndexManagerController
 */
class ResourceTrashManagerController extends modManagerController
{
    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs()
    {
        $mgrUrl = $this->modx->getOption('manager_url', null, MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.grid.trash.js');
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.panel.trash.js');
        $this->addJavascript($mgrUrl . 'assets/modext/sections/resource/trash/index.js');
        $this->addHtml('<script>Ext.onReady(function() { MODx.add("modx-page-trash"); });</script>');
    }

    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('trash.page_title');
    }

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('menu_trash');
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['trash', 'namespace'];
    }

    /**
     * Process the controller, returning an array of placeholders to set.
     * @param array $scriptProperties A array of REQUEST parameters.
     * @return mixed Either an error or output string, or an array of placeholders to set.
     */
    public function process(array $scriptProperties = [])
    {
        return null;
    }

    /**
     * Return the relative path to the template file to load
     * @return string
     */
    public function getTemplateFile()
    {
        return '';
    }
}
