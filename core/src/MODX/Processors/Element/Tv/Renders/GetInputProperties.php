<?php

namespace MODX\Processors\Element\Tv\Renders;

use MODX\Processors\modProcessor;
use MODX\modTemplateVar;

/**
 * Grabs a list of input properties for a TV type
 *
 * @param string $context (optional) The context by which to grab renders from. Defaults to
 * executing context.
 * @param string $type (optional) The type of render to grab properties for.
 * Defaults to default.
 * @param integer $tv (optional) The TV to prefill property values from.
 *
 * @package modx
 * @subpackage processors.element.tv.renders
 */
class GetInputProperties extends modProcessor
{

    public $propertiesKey = 'input_properties';
    public $renderDirectory = 'InputProperties';
    public $onPropertiesListEvent = 'OnTVInputPropertiesList';


    public function checkPermissions()
    {
        return $this->modx->hasPermission('view_tv');
    }


    public function getLanguageTopics()
    {
        return ['tv_widget', 'tv_input_types'];
    }


    public function initialize()
    {
        /* simulate controller to allow controller methods in TV Input Properties controllers */
        $this->modx->getService('smarty', 'smarty.modSmarty', '');

        $context = $this->getProperty('context');
        if (empty($context)) {
            $this->setProperty('context', $this->modx->context->get('key'));
        }
        $this->setDefaultProperties([
            'type' => 'Defaults',
        ]);

        return true;
    }


    public function process()
    {
        $this->renderController();
        $this->getInputProperties();

        $renderDirectories = $this->getRenderDirectories();

        return $this->getRenderOutput($renderDirectories);
    }


    /**
     * Get the properties render output when given an array of directories to search
     *
     * @param array $renderDirectories
     *
     * @return mixed|string
     */
    public function getRenderOutput(array $renderDirectories)
    {
        $type = implode('', array_map('ucfirst', explode('-', $this->getProperty('type'))));
        $o = '';
        foreach ($renderDirectories as $renderDirectory) {
            if (empty($renderDirectory) || !is_dir($renderDirectory)) {
                continue;
            }

            $renderFile = $renderDirectory . $type . '.php';

            if (file_exists($renderFile)) {
                $modx =& $this->modx;
                @ob_start();
                $o = include $renderFile;
                @ob_end_clean();
                break;
            }
        }

        return $o;
    }


    /**
     * Simulate controller with the faux controller class
     *
     * @return string
     */
    public function renderController()
    {
        $c = new PropertiesController($this->modx);
        $this->modx->controller = call_user_func_array([$c, 'getInstance'], [&$this->modx, PropertiesController::class]);

        return $this->modx->controller->render();
    }


    /**
     * Get default display properties for specific tv
     *
     * @return array
     */
    public function getInputProperties()
    {
        $settings = [];
        $tvId = $this->getProperty('tv');
        if (!empty($tvId)) {
            /** @var modTemplateVar $tv */
            $tv = $this->modx->getObject('modTemplateVar', $tvId);
            if (is_object($tv) && $tv instanceof modTemplateVar) {
                $settings = $tv->get($this->propertiesKey);

            }
            $this->modx->controller->setPlaceholder('tv', $tvId);
        }
        if (!isset($settings['allowBlank'])) {
            $settings['allowBlank'] = true;
        }
        $this->modx->controller->setPlaceholder('params', $settings);

        return $settings;
    }


    /**
     * Fire event to allow for custom directories
     *
     * @return array
     */
    public function fireOnTVPropertiesListEvent()
    {
        $pluginResult = $this->modx->invokeEvent($this->onPropertiesListEvent, [
            'context' => $this->getProperty('context'),
        ]);
        if (!is_array($pluginResult) && !empty($pluginResult)) {
            $pluginResult = [$pluginResult];
        }

        return !empty($pluginResult) ? $pluginResult : [];
    }


    /**
     * Load namespace cached directories
     *
     * @return array
     */
    public function loadNamespaceCache()
    {
        $cache = $this->modx->call('modNamespace', 'loadCache', [&$this->modx]);
        $cachedDirs = [];
        if (!empty($cache) && is_array($cache)) {
            foreach ($cache as $namespace) {
                $inputDir = rtrim($namespace['path'], '/') . '/tv/' . $this->renderDirectory . '/';
                if (is_dir($inputDir)) {
                    $cachedDirs[] = $inputDir;
                }
            }
        }

        return $cachedDirs;
    }


    /**
     * @return array
     */
    public function getRenderDirectories()
    {
        /* handle dynamic paths */
        $renderDirectories = [
            dirname(__FILE__) . '/' . ucfirst($this->getProperty('context')) . '/' . $this->renderDirectory . '/',
        ];

        $pluginResult = $this->fireOnTVPropertiesListEvent();
        $cached = $this->loadNamespaceCache();
        $renderDirectories = array_merge($renderDirectories, $pluginResult, $cached);

        return $renderDirectories;
    }
}