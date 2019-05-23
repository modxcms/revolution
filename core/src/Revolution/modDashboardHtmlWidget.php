<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution;


/**
 * A widget that contains only HTML.
 *
 * @package MODX\Revolution
 */
class modDashboardHtmlWidget extends modDashboardWidgetInterface
{
    public function render()
    {
        return $this->widget->get('content');
    }
}
