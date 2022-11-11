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


use Throwable;
use xPDO\xPDO;

/**
 * Abstract class used for creating Dashboard Widgets. In your widget file, simply return the name of the class
 * at the end of the file, and MODX will instantiate your class as a widget, and run the render() method to get
 * the widget output.
 *
 * @package MODX\Revolution
 */
abstract class modDashboardWidgetInterface
{
    /**
     * A reference to the modX|xPDO instance
     * @var xPDO|modX $modx
     */
    public $modx;
    /**
     * A reference to the currently loaded manager controller
     * @var modManagerController $controller
     */
    public $controller;
    /**
     * A reference to this class's widget
     * @var modDashboardWidget $widget
     */
    public $widget;
    /**
     * A reference to the Namespace that this widget is executing in
     * @var modNamespace $namespace
     */
    public $namespace;
    /**
     * Allows widgets to specify a CSS class to attach to the block
     *
     * @var string
     */
    public $cssBlockClass = '';
    /**
     * @var string
     */
    public $content = '';

    /**
     * @param xPDO|modX $modx A reference to the modX instance
     * @param modDashboardWidget $widget A reference to this class's widget
     * @param modManagerController $controller A reference to the currently loaded manager controller
     */
    function __construct(xPDO &$modx,modDashboardWidget &$widget,modManagerController &$controller) {
        $this->modx =& $modx;
        $this->widget =& $widget;
        $this->controller =& $controller;
    }

    /**
     * Set the widget content
     * @param string $content
     * @return void
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * Renders the content of the block in the appropriate size
     * @return string
     */
    public function process() {
        try {
            $output = $this->render();
        } catch (Throwable $t) {
            $this->controller->setPlaceholder('_e', [
                'message' => $t->getMessage(),
                'errors' => explode("\n", $t->getTraceAsString()),
            ]);
            $output = $this->controller->fetchTemplate('error.tpl');
        }

        if (!empty($output)) {
            $widgetArray = $this->widget->toArray();
            $widgetArray['content'] = $output;
            $widgetArray['class'] = $this->cssBlockClass;
            $output = $this->getFileChunk('dashboard/block.tpl',$widgetArray);
            $output = preg_replace('/\[\[([^\[\]]++|(?R))*?]]/s', '', $output);
        }
        return $output;
    }

    /**
     * Sets the Namespace that this widget will execute in
     * @param modNamespace $namespace
     * @return void
     */
    public function setNamespace(modNamespace $namespace) {
        $this->namespace =& $namespace;
    }

    /**
     * Returns an array of placeholders for the block from the widget class. Override to add or change placeholders.
     * @return array
     */
    public function toArray() {
        return $this->widget->toArray();
    }

    /**
     * Must be declared in your derivative class. Must return the processed output of the widget.
     * @abstract
     * @return string
     */
    abstract public function render();

    /**
     * @param string $tpl
     * @param array $placeholders
     * @return string
     */
    public function getFileChunk($tpl,array $placeholders = []) {
        $output = '';
        $file = $tpl;
        if (!file_exists($file)) {
            $file = $this->modx->getOption('manager_path').'templates/'.$this->modx->getOption('manager_theme',null,'default').'/'.$tpl;
        }
        if (!file_exists($file)) {
            $file = $this->modx->getOption('manager_path').'templates/default/'.$tpl;
        }
        if (file_exists($file)) {
            /** @var modChunk $chunk */
            $chunk = $this->modx->newObject(modChunk::class);
            $chunk->setCacheable(false);
            $tplContent = file_get_contents($file);
            $chunk->setContent($tplContent);
            $output = $chunk->process($placeholders);
        }
        return $output;
    }

    /**
     * Render the widget content as if it were a Snippet
     *
     * @param string $content
     * @return string
     */
    public function renderAsSnippet($content = '')
    {
        if (empty($content)) {
            $content = $this->widget->get('content');
        }
        $content = str_replace(['<?php', '?>'], '', $content);
        $closure = function ($scriptProperties) use ($content) {
            global $modx;
            if (is_array($scriptProperties)) {
                extract($scriptProperties, EXTR_SKIP);
            }

            return eval($content);
        };

        return $closure([
            'controller' => $this->controller,
        ]);
    }
}
