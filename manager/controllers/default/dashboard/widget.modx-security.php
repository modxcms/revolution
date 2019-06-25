<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modDashboardWidgetInterface;

/**
 * @package modx
 * @subpackage dashboard
 */
class modDashboardWidgetSecurityFeed extends modDashboardWidgetInterface
{
    public function render()
    {
        $enabled = $this->modx->getOption('feed_modx_security_enabled', null, true);
        if (!$enabled) {
            return '';
        }

        return '<div id="modx-security-feed-container" class="feed-loading" data-feed="news"><i class="icon icon-refresh icon-spin" aria-hidden="true"></i> ' . $this->modx->lexicon('loading') . '</div>';
    }
}

return 'modDashboardWidgetSecurityFeed';
