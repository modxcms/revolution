<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;
use xPDO\xPDO;

/**
 * Abstraction of a Dashboard Widget, which can be placed on Dashboards for welcome screen customization.
 *
 * @property string                        $name        The name of this widget
 * @property string                        $description A short description, or lexicon key, of this widget
 * @property string                        $type        The type of widget that this is: file/snippet/html
 * @property string                        $content     The content, or location, of this widget
 * @property array                         $properties
 * @property string                        $namespace   The namespace key of this widget
 * @property string                        $lexicon     The lexicon that will be loaded for this widget's description/execution
 * @property string                        $size
 * @property string                        $permission
 *
 * @property modDashboardWidgetPlacement[] $Placements
 *
 * @property modX|xPDO                     $xpdo
 *
 * @package MODX\Revolution
 */
class modDashboardWidget extends xPDOSimpleObject
{
    public function toArray($keyPrefix = '', $rawValues = false, $excludeLazy = false, $includeRelated = false)
    {
        $array = parent::toArray($keyPrefix, $rawValues, $excludeLazy);

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
     *
     * @return mixed
     */
    public function getContent($controller)
    {
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
                $content = str_replace([
                    '[[++base_path]]',
                    '[[++core_path]]',
                    '[[++manager_path]]',
                    '[[++assets_path]]',
                    '[[++manager_theme]]',
                ], [
                    $this->xpdo->getOption('base_path', null, MODX_BASE_PATH),
                    $this->xpdo->getOption('core_path', null, MODX_CORE_PATH),
                    $this->xpdo->getOption('manager_path', null, MODX_MANAGER_PATH),
                    $this->xpdo->getOption('assets_path', null, MODX_ASSETS_PATH),
                    $this->xpdo->getOption('manager_theme', null, 'default'),
                ], $content);
                if (file_exists($content)) {
                    $modx =& $this->xpdo;
                    $scriptProperties = $this->toArray();
                    ob_start();
                    $className = include_once $content;
                    $buffer = ob_get_contents();
                    ob_end_clean();
                    if (class_exists($className)) { /* is a class-based widget */
                        /** @var modDashboardWidgetInterface $widget */
                        $widget = new $className($this->xpdo, $this, $controller);
                    } else { /* just a standard file with a return */
                        if (($className === 1 || $className === true) && !empty($buffer)) {
                            $className = $buffer;
                        }
                        $widget = new modDashboardFileWidget($this->xpdo, $this, $controller);
                        $widget->setContent($className);
                    }
                }
                break;
            /* Snippet widget */
            case 'snippet':
                $widget = new modDashboardSnippetWidget($this->xpdo, $this, $controller);
                break;
            /* PHP (Snippet) widget */
            case 'php':
                $widget = new modDashboardPhpWidget($this->xpdo, $this, $controller);
                break;
            /* HTML/static content widget */
            case 'html':
            default:
                $widget = new modDashboardHtmlWidget($this->xpdo, $this, $controller);
                break;

        }

        // Make sure we actually have a widget before proceeding
        if ($widget === null) {
            if ($controller === null) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Failed to load dashboard widget with unknown controller.');

                return null;
            }

            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,
                'Failed to load dashboard widget with controller title "' . $controller->getPageTitle() . '".');

            return null;
        }

        if (!empty($namespace)) {
            $widget->setNamespace($namespace);
        }

        return $widget->process();
    }
}
