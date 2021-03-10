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
 * "Media Browser" controller
 */
class MediaBrowserManagerController extends modManagerController
{
    /**
     * @inherit
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('file_manager');
    }

    /**
     * Register custom CSS/JS for the page
     *
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addHtml(
<<<HTML
<script>
// <![CDATA[
    Ext.onReady(function() {
        Ext.getCmp('modx-layout').hideLeftbar(true, false);
        MODx.add('modx-media-view');
    });
// ]]>
</script>
HTML
        );
    }

    /**
     * @inherit
     */
    public function process(array $scriptProperties = [])
    {
        return [];
    }

    /**
     * @inherit
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('modx_browser');
    }

    /**
     * @inherit
     */
    public function getTemplateFile()
    {
        return '';
    }

    /**
     * @inherit
     */
    public function getLanguageTopics()
    {
        return ['file'];
    }
}
