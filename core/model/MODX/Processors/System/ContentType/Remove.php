<?php

namespace MODX\Processors\System\ContentType;

use MODX\Processors\modObjectRemoveProcessor;

/**
 * Removes a content type
 *
 * @param integer $id The ID of the content type
 *
 * @package modx
 * @subpackage processors.system.contenttype
 */
class Remove extends modObjectRemoveProcessor
{
    public $classKey = 'modContentType';
    public $languageTopics = ['content_type'];
    public $permission = 'content_types';
    public $objectType = 'content_type';


    public function beforeRemove()
    {
        if ($this->isInUse()) {
            return $this->modx->lexicon('content_type_err_in_use');
        }

        return true;
    }


    public function isInUse()
    {
        return $this->modx->getCount('modResource', ['content_type' => $this->object->get('id')]) > 0;
    }
}