<?php

namespace MODX;

use MODX\modDashboardWidgetInterface;

/**
 * A file-based widget that returns only the content of its include.
 *
 * @package modx
 * @subpackage dashboard
 */
class modDashboardFileWidget extends modDashboardWidgetInterface
{
    public function render()
    {
        return $this->content;
    }
}