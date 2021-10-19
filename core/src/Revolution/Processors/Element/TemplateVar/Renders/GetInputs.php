<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\TemplateVar\Renders;

use DirectoryIterator;
use MODX\Revolution\modNamespace;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\Processors\Element\TemplateVar\Renders\Controllers\TvInputManagerController;
use UnexpectedValueException;

/**
 * Grabs a list of inputs for a TV.
 *
 * @param string $context (optional) The context by which to grab renders from. Defaults to
 * executing context.
 *
 * @package MODX\Revolution\Processors\Element\TemplateVar\Renders
 */
class GetInputs extends Processor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('view_tv');
    }
    public function getLanguageTopics()
    {
        return ['tv_widget','tv_input_types'];
    }

    public function initialize()
    {
        /* simulate controller to allow controller methods in TV Input Properties controllers */
        $this->modx->getService('smarty', 'MODX\Revolution\Smarty\modSmarty', '');
        return true;
    }

    public function process()
    {
        $context = $this->getProperty('context', $this->modx->context->get('key'));

        /* simulate controller with the faux class above */
        $c = new TvInputManagerController($this->modx);
        $this->modx->controller = call_user_func_array(
            [$c, 'getInstance'],
            [&$this->modx, TvInputManagerController::class]
        );
        $this->modx->controller->render();

        $renderDirectories = [__DIR__ . '/' . $context . '/input/'];

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
        $cache = $this->modx->call(modNamespace::class, 'loadCache', [&$this->modx]);
        if (!empty($cache) && is_array($cache)) {
            foreach ($cache as $namespace) {
                $inputDir = rtrim($namespace['path'], '/') . '/tv/input/';
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
                $dirIterator = new DirectoryIterator($renderDirectory);
                foreach ($dirIterator as $file) {
                    if (!$file->isReadable() || !$file->isFile()) {
                        continue;
                    }
                    $type = str_replace(['.php','.class','.class.php'], '', $file->getFilename());
                    $types[$type] = [
                        'name' => $this->modx->lexicon($type),
                        'value' => $type,
                    ];
                }
            } catch (UnexpectedValueException $e) {
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
