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
 * A base manager controller to implement, which makes use of the regular parser
 *
 * @package MODX\Revolution
 */
abstract class modParsedManagerController extends modExtraManagerController
{
    /**
     * The request HTTP method
     *
     * @var string
     */
    protected $method = 'GET';

    public function initialize()
    {
        parent::initialize();
        // Let's check the HTTP method to display a different content based on it (ie. when we submit a form)
        $this->method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        // Some hack to put the HTML content in its right place so ExtJS can resize the main content "area" properly
        $this->addHtml(<<<HTML
<script>
Ext.onReady(function() {
    var node = document.getElementById('modx-panel-holder').nextElementSibling
        ,content = node.innerHTML;
    node.parentNode.removeChild(node);
    MODx.add({
        xtype: 'box'
        ,html: content
        ,cls: node.className || ''
        ,id: node.id || Ext.id()
    });
});
</script>
HTML
        );
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $html = parent::render();
        // Make controller placeholders available as modx placeholders
        $this->modx->setPlaceholders($this->placeholders, 'ph.');
        // Make script properties available as placeholders too
        $this->modx->setPlaceholders($this->scriptProperties, 'prop.');
        // Make modx parses tags
        $this->modx->getParser()->processElementTags('', $html, true, true);

        return $html;
    }
}
