<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\Plugin;


use MODX\Revolution\modPlugin;
use MODX\Revolution\Sources\modFileMediaSource;

/**
 * Delete a plugin.
 *
 * @property integer $id The ID of the plugin
 *
 * @package MODX\Revolution\Processors\Element\Plugin
 */
class Remove extends \MODX\Revolution\Processors\Element\Remove
{
    public $classKey = modPlugin::class;
    public $languageTopics = ['plugin'];
    public $permission = 'delete_plugin';
    public $objectType = 'plugin';
    public $beforeRemoveEvent = 'OnBeforePluginFormDelete';
    public $afterRemoveEvent = 'OnPluginFormDelete';

    public $staticFile = '';
    public $staticFilePath = '';

    public function beforeRemove()
    {
        if ($this->object->get('static_file')) {
            /** @var modFileMediaSource $source */
            $source = $this->modx->getObject(modFileMediaSource::class, ['id' => $this->object->get('source')]);
            if ($source && $source->get('is_stream')) {
                $source->initialize();
                $this->staticFile = $this->object->get('static_file');
                $this->staticFilePath = $source->getBasePath() . $this->object->get('static_file');
            }
        }

        return true;
    }

    public function afterRemove()
    {
        $this->cleanupStaticFiles();

        return true;
    }
}
