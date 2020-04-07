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
 * A Snippet-based widget that loads a MODX Snippet to return its content.
 *
 * @package MODX\Revolution
 */
class modDashboardSnippetWidget extends modDashboardWidgetInterface
{
    public function render()
    {
        /** @var modSnippet $snippet */
        $snippet = $this->modx->getObject(modSnippet::class,
                                          [
            'name' => $this->widget->get('content'),
                                          ]
        );
        if ($snippet) {
            $snippet->setCacheable(false);
            $content = $snippet->process(
                [
                'controller' => $this->controller,
                ]
            );
        } else {
            $content = '';
        }
        return $content;
    }
}
