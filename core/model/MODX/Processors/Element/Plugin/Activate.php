<?php

namespace MODX\Processors\Element\Plugin;

use MODX\Processors\modObjectUpdateProcessor;

/**
 * Activate a plugin.
 *
 * @param integer $id The ID of the plugin.
 *
 * @package modx
 * @subpackage processors.element.plugin
 */
class Activate extends modObjectUpdateProcessor
{
    public $classKey = 'modPlugin';
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
