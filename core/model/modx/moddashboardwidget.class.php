<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Abstraction of a Dashboard Widget, which can be placed on Dashboards for welcome screen customization.
 *
 * @property string $name The name of this widget
 * @property string $description A short description, or lexicon key, of this widget
 * @property string $type The type of widget that this is: file/snippet/html
 * @property string $content The content, or location, of this widget
 * @property string $namespace The namespace key of this widget
 * @property string $lexicon The lexicon that will be loaded for this widget's description/execution
 *
 * @property modNamespace $Namespace The Namespace this widget is related to
 * @property array $Placements An array of Placements this widget has been put on in Dashboards
 * @package modx
 */
class modDashboardWidget extends xPDOSimpleObject {
    public function toArray($keyPrefix = '',$rawValues = false,$excludeLazy = false,$includeRelated = false) {
        $array = parent::toArray($keyPrefix,$rawValues,$excludeLazy);

        if (!empty($this->xpdo->lexicon) && $this->xpdo->lexicon instanceof modLexicon) {
            if ($this->get('lexicon') != 'core:dashboards') {
                $this->xpdo->lexicon->load($this->get('lexicon'));
            }
            $array['name_trans'] = $this->xpdo->lexicon->exists($this->get('name')) ? $this->xpdo->lexicon($this->get('name')) : $this->get('name');
            $array['description_trans'] = $this->xpdo->lexicon->exists($this->get('description')) ? $this->xpdo->lexicon($this->get('description')) : $this->get('description');
        }
        return $array;

    }
    /**
     * Return the output for the widget, processed by type
     *
     * @param modManagerController $controller
     * @return mixed
     */
    public function getContent($controller) {
        /** @var string $lexicon */
        $lexicon = $this->get('lexicon');
        if (!empty($lexicon) && !empty($this->xpdo->lexicon)) {
            $this->xpdo->lexicon->load($lexicon);
        }
        /** @var modNamespace $namespace */
        $namespace = $this->getOne('Namespace');
        /** @var string $content */
        $content = $this->get('content');
        /** @var modDashboardWidgetInterface $widget */
        $widget = null;
        switch (strtolower($this->get('type'))) {
            /* file/class-based widget */
            case 'file':
                $content = str_replace(array(
                    '[[++base_path]]',
                    '[[++core_path]]',
                    '[[++manager_path]]',
                    '[[++assets_path]]',
                    '[[++manager_theme]]',
                ),array(
                    $this->xpdo->getOption('base_path',null,MODX_BASE_PATH),
                    $this->xpdo->getOption('core_path',null,MODX_CORE_PATH),
                    $this->xpdo->getOption('manager_path',null,MODX_MANAGER_PATH),
                    $this->xpdo->getOption('assets_path',null,MODX_ASSETS_PATH),
                    $this->xpdo->getOption('manager_theme',null,'default'),
                ),$content);
                if (file_exists($content)) {
                    $modx =& $this->xpdo;
                    $scriptProperties = $this->toArray();
                    ob_start();
                    $className = include_once $content;
                    $buffer = ob_get_contents();
                    ob_end_clean();
                    if (class_exists($className)) { /* is a class-based widget */
                        /** @var modDashboardWidgetInterface $widget */
                        $widget = new $className($this->xpdo,$this,$controller);
                    } else { /* just a standard file with a return */
                        if (($className === 1 || $className === true) && !empty($buffer)) {
                            $className = $buffer;
                        }
                        $widget = new modDashboardFileWidget($this->xpdo,$this,$controller);
                        $widget->setContent($className);
                    }
                }
                break;
            /* Snippet widget */
            case 'snippet':
                $widget = new modDashboardSnippetWidget($this->xpdo,$this,$controller);
                break;
            /* PHP (Snippet) widget */
            case 'php':
                $widget = new modDashboardPhpWidget($this->xpdo,$this,$controller);
                break;
            /* HTML/static content widget */
            case 'html':
            default:
                $widget = new modDashboardHtmlWidget($this->xpdo,$this,$controller);
                break;

        }

        // Make sure we actually have a widget before proceeding
        if ($widget === null) {
            if ($controller === null) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Failed to load dashboard widget with unknown controller.');
                return null;
            }

            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Failed to load dashboard widget with controller title "' . $controller->getPageTitle() . '".');
            return null;
        }

        if (!empty($namespace)) {
            $widget->setNamespace($namespace);
        }

        return $widget->process();
    }
}

/**
 * A file-based widget that returns only the content of its include.
 *
 * @package modx
 * @subpackage dashboard
 */
class modDashboardFileWidget extends modDashboardWidgetInterface {
    public function render() {
        return $this->content;
    }
}

/**
 * A widget that contains only HTML.
 *
 * @package modx
 * @subpackage dashboard
 */
class modDashboardHtmlWidget extends modDashboardWidgetInterface {
    public function render() {
        return $this->widget->get('content');
    }
}

/**
 * A widget that runs its content as PHP in Snippet-like format to get its content.
 * @package modx
 * @subpackage dashboard
 */
class modDashboardPhpWidget extends modDashboardWidgetInterface {
    public function render() {
        return $this->renderAsSnippet();
    }
}

/**
 * A Snippet-based widget that loads a MODX Snippet to return its content.
 * @package modx
 * @subpackage dashboard
 */
class modDashboardSnippetWidget extends modDashboardWidgetInterface {
    public function render() {
        /** @var modSnippet $snippet */
        $snippet = $this->modx->getObject('modSnippet',array(
            'name' => $this->widget->get('content'),
        ));
        if ($snippet) {
            $snippet->setCacheable(false);
            $content = $snippet->process(array(
                'controller' => $this->controller,
            ));
        } else {
            $content = '';
        }
        return $content;
    }
}

/**
 * Abstract class used for creating Dashboard Widgets. In your widget file, simply return the name of the class
 * at the end of the file, and MODX will instantiate your class as a widget, and run the render() method to get
 * the widget output.
 *
 * @abstract
 * @package modx
 * @subpackage dashboard
 */
abstract class modDashboardWidgetInterface {
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
        $output = $this->render();
        if (!empty($output)) {
            $widgetArray = $this->widget->toArray();
            $widgetArray['content'] = $output;
            $widgetArray['class'] = $this->cssBlockClass;
            $output = $this->getFileChunk('dashboard/block.tpl',$widgetArray);
            $output = preg_replace('@\[\[(.[^\[\[]*?)\]\]@si','',$output);
            $output = preg_replace('@\[\[(.[^\[\[]*?)\]\]@si','',$output);
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
    public function getFileChunk($tpl,array $placeholders = array()) {
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
            $chunk = $this->modx->newObject('modChunk');
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
    public function renderAsSnippet($content = '') {
        if (empty($content)) $content = $this->widget->get('content');
        $content = str_replace(array('<?php','?>'),'',$content);
        $closure = create_function('$scriptProperties','global $modx;if (is_array($scriptProperties)) {extract($scriptProperties, EXTR_SKIP);}'.$content);
        return $closure(array(
            'controller' => $this->controller,
        ));
    }
}
