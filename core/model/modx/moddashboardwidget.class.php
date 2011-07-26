<?php
/**
 * @package modx
 * @subpackage mysql
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
                    $className = include $content;
                    if (class_exists($className)) { /* is a class-based widget */
                        /** @var modDashboardWidgetClass $widget */
                        $widget = new $className($this->xpdo,$this,$controller);
                        if (!empty($namespace)) {
                            $widget->setNamespace($namespace);
                        }
                        $content = $widget->getContent();
                    } else { /* just a standard file with a return */
                        $content = $className;
                    }
                }
                break;
            /* Snippet widget */
            case 'snippet':
                /** @var modSnippet $snippet */
                $snippet = $this->xpdo->newObject('modSnippet');
                $snippet->setContent($this->get('content'));
                $snippet->setCacheable(false);
                $content = $snippet->process(array(
                    'controller' => $controller,
                ));
                break;
            /* HTML/static content widget */
            case 'html':
            default:
                break;

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
abstract class modDashboardWidgetClass {
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
     * Renders the content of the block in the appropriate size
     * @return string
     */
    public function getContent() {
        $output = $this->render();
        $widgetArray = $this->toArray();
        $widgetArray['content'] = $output;
        $widgetArray['class'] = $this->cssBlockClass;
        $output = $this->getFileChunk('dashboard/block.tpl',$widgetArray);
        $output = preg_replace('@\[\[(.[^\[\[]*?)\]\]@si','',$output);
        $output = preg_replace('@\[\[(.[^\[\[]*?)\]\]@si','',$output);
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
        if (!file_exists($tpl)) {
            $tpl = $this->modx->getOption('manager_path').'templates/'.$this->modx->getOption('manager_theme',null,'default').'/'.$tpl;
        }
        if (file_exists($tpl)) {
            /** @var modChunk $chunk */
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setCacheable(false);
            $tplContent = file_get_contents($tpl);
            $chunk->setContent($tplContent);
            $output = $chunk->process($placeholders);
        }
        return $output;
    }
}