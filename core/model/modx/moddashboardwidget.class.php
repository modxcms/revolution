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
     * @return mixed
     */
    public function getContent() {
        $lexicon = $this->get('lexicon');
        if (!empty($lexicon) && !empty($this->xpdo->lexicon)) {
            $this->xpdo->lexicon->load($lexicon);
        }
        $content = $this->get('content');
        switch (strtolower($this->get('type'))) {
            /* file/class-based widget */
            case 'file':
                $content = str_replace(array(
                    '[[++base_path]]',
                    '[[++core_path]]',
                    '[[++manager_path]]',
                    '[[++assets_path]]',
                ),array(
                    $this->xpdo->getOption('base_path',null,MODX_BASE_PATH),
                    $this->xpdo->getOption('core_path',null,MODX_CORE_PATH),
                    $this->xpdo->getOption('manager_path',null,MODX_MANAGER_PATH),
                    $this->xpdo->getOption('assets_path',null,MODX_ASSETS_PATH),
                ),$content);
                if (file_exists($content)) {
                    $className = include $content;
                    if (class_exists($className)) { /* is a class-based widget */
                        /** @var modDashboardWidgetClass $widget */
                        $widget = new $className($this->xpdo);
                        $content = $widget->render();
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
                $content = $snippet->process();
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

    function __construct(xPDO &$modx) {
        $this->modx =& $modx;
    }

    /**
     * Must be declared in your derivative class. Must return the processed output of the widget.
     * @abstract
     * @return string
     */
    abstract public function render();
}