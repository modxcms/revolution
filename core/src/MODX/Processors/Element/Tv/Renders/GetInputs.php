<?php

namespace MODX\Processors\Element\Tv\Renders;

use MODX\Processors\modProcessor;

/**
 * Grabs a list of inputs for a TV.
 *
 * @param string $context (optional) The context by which to grab renders from. Defaults to
 * executing context.
 *
 * @package modx
 * @subpackage processors.element.tv.renders
 */
class GetInputs extends modProcessor
{
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
        $this->modx->getService('smarty', 'smarty.modSmarty');

        return true;
    }


    public function process()
    {
        $context = $this->getProperty('context', $this->modx->context->get('key'));
        /* simulate controller with the faux class above */
        $c = new PropertiesController($this->modx);
        $this->modx->controller = call_user_func_array([$c, 'getInstance'], [&$this->modx, PropertiesController::class]);
        $this->modx->controller->render();

        $renderDirectories = [
            dirname(__FILE__) . '/' . ucfirst($context) . '/Input/',
        ];

        /* allow for custom directories */
        $pluginResult = $this->modx->invokeEvent('OnTVInputRenderList', [
            'context' => $context,
        ]);
        if (!is_array($pluginResult) && !empty($pluginResult)) {
            $pluginResult = [$pluginResult];
        }
        if (!empty($pluginResult)) {
            $renderDirectories = array_merge($renderDirectories, $pluginResult);
        }

        /* load namespace caches */
        $cache = $this->modx->call('MODX\modNamespace', 'loadCache', [&$this->modx]);
        if (!empty($cache) && is_array($cache)) {
            foreach ($cache as $namespace) {
                $inputDir = rtrim($namespace['path'], '/') . '/Tv/Input/';
                if (is_dir($inputDir)) {
                    $renderDirectories[] = $inputDir;
                }
            }
        }

        /* search directories */
        $types = [];
        foreach ($renderDirectories as $renderDirectory) {
            if (empty($renderDirectory) || !is_dir($renderDirectory)) {
                continue;
            }
            try {
                $dirIterator = new \DirectoryIterator($renderDirectory);
                foreach ($dirIterator as $file) {
                    if (!$file->isReadable() || !$file->isFile()) {
                        continue;
                    }
                    $type = str_replace(['.php', '.class', '.class.php'], '', $file->getFilename());

                    $types[$type] = [
                        'name' => $this->modx->lexicon($type),
                        'value' => $type,
                    ];
                }
            } catch (\UnexpectedValueException $e) {
            }
        }

        /* sort types */
        asort($types);
        $otypes = [];
        foreach ($types as $type) {
            $otypes[] = $type;
        }

        return $this->outputArray($otypes);
    }
}