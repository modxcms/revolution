<?php

namespace MODX\Processors\Element\Tv\Renders;

use MODX\Processors\modProcessor;

/**
 * Grabs a list of output renders for the tv.
 *
 * @param string $context (optional) The context by which to grab renders from. Defaults to web.
 *
 * @package modx
 * @subpackage processors.element.tv.renders
 */
class GetOutputs extends modProcessor
{

    /**
     * Check permissions to view TV
     *
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('view_tv');
    }


    /**
     * Load Language Topics for this processor.
     *
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['tv_widget'];
    }


    /**
     * Fire event to allow for custom directories
     *
     * @param $context
     *
     * @return array
     */
    public function fireOnTVOutputRenderListEvent($context)
    {
        $pluginResult = $this->modx->invokeEvent('OnTVOutputRenderList', [
            'context' => $context,
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
                $inputDir = rtrim($namespace['path'], '/') . '/Tv/Output/';
                if (is_dir($inputDir)) {
                    $cachedDirs[] = $inputDir;
                }
            }
        }

        return $cachedDirs;
    }


    /**
     * Get directories where to find TV output types
     *
     * @return array
     */
    public function getRenderDirectories()
    {
        $context = $this->getProperty('context', 'web');

        $renderDirectories = [
            dirname(__FILE__) . '/' . ucfirst($context) . '/Output/',
        ];

        $pluginResult = $this->fireOnTVOutputRenderListEvent($context);
        $cached = $this->loadNamespaceCache();
        $renderDirectories = array_merge($renderDirectories, $pluginResult, $cached);

        return $renderDirectories;
    }


    /**
     * Find TV output types
     *
     * @param array $data
     *
     * @return array
     */
    public function iterate(array $data)
    {
        $types = [];
        foreach ($data as $renderDirectory) {
            if (empty($renderDirectory) || !is_dir($renderDirectory)) {
                continue;
            }
            try {
                $dirIterator = new \DirectoryIterator($renderDirectory);
                foreach ($dirIterator as $file) {
                    if (!$file->isReadable() || !$file->isFile()) continue;
                    $type = str_replace(['.php', '.class', '.class.php'], '', $file->getFilename());
                    $types[$type] = [
                        'name' => $this->modx->lexicon($type),
                        'value' => $type,
                    ];
                }
            } catch (\UnexpectedValueException $e) {
            }
        }

        return $types;
    }


    /**
     * {@inheritdoc}
     * @return mixed|string
     */
    public function process()
    {
        $renderDirectories = $this->getRenderDirectories();
        $types = $this->iterate($renderDirectories);

        return $this->cleanup($types);
    }


    /**
     * Prepare list of types for response
     *
     * @param array $types
     *
     * @return string
     */
    public function cleanup(array $types)
    {
        ksort($types);
        $types = array_values($types);

        return $this->outputArray($types);
    }
}