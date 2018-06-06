<?php

namespace MODX;


/**
 * A widget that contains only HTML.
 *
 * @package modx
 * @subpackage dashboard
 */
class modDashboardHtmlWidget extends modDashboardWidgetInterface
{
    public function render()
    {
        return $this->widget->get('content');
    }
}