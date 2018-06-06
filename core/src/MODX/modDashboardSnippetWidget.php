<?php

namespace MODX;

/**
 * A Snippet-based widget that loads a MODX Snippet to return its content.
 *
 * @package modx
 * @subpackage dashboard
 */
class modDashboardSnippetWidget extends modDashboardWidgetInterface
{
    public function render()
    {
        /** @var modSnippet $snippet */
        $snippet = $this->modx->getObject('modSnippet', [
            'name' => $this->widget->get('content'),
        ]);
        if ($snippet) {
            $snippet->setCacheable(false);
            $content = $snippet->process([
                'controller' => $this->controller,
            ]);
        } else {
            $content = '';
        }

        return $content;
    }
}
