<?php

namespace MODX\Processors\Element\Tv;

/**
 * Gets a TV
 *
 * @param integer $id The ID of the TV
 *
 * @package modx
 * @subpackage processors.element.tv
 */
class Get extends \MODX\Processors\Element\Get
{
    public $classKey = 'modTemplateVar';
    public $languageTopics = ['tv', 'category'];
    public $permission = 'view_tv';
    public $objectType = 'tv';


    public function beforeOutput()
    {
        parent::beforeOutput();
        $this->object->set('els', $this->object->get('elements'));
    }
}