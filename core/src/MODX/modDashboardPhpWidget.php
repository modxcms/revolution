<?php

namespace MODX;

/**
 * A widget that runs its content as PHP in Snippet-like format to get its content.
 *
 * @package modx
 * @subpackage dashboard
 */
class modDashboardPhpWidget extends modDashboardWidgetInterface
{
    public function render()
    {
        return $this->renderAsSnippet();
    }
}