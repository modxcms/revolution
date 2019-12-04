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


use MODX\Revolution\Processors\Model\UpdateProcessor;
use MODX\Revolution\modPlugin;

/**
 * Activate a plugin.
 *
 * @property integer $id The ID of the plugin.
 *
 * @package MODX\Revolution\Processors\Element\Plugin
 */
class Activate extends UpdateProcessor
{
    public $classKey = modPlugin::class;
    public $languageTopics = ['plugin', 'category', 'element'];
    public $permission = 'save_plugin';
    public $objectType = 'plugin';
    public $checkViewPermission = false;

    public function beforeSave()
    {
        $this->object->set('disabled', false);

        return parent::beforeSave();
    }

    public function afterSave()
    {
        $this->modx->cacheManager->refresh();

        return parent::afterSave();
    }

    public function cleanup()
    {
        return $this->success('', [$this->object->get('id')]);
    }
}
