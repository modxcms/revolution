<?php

namespace MODX\Processors\Element;

use MODX\Processors\modObjectDuplicateProcessor;

/**
 * Abstract class for Duplicate Element processors. To be extended for each derivative element type.
 *
 * @abstract
 * @package modx
 * @subpackage processors.element
 */
class Duplicate extends modObjectDuplicateProcessor
{
    public function cleanup()
    {
        return $this->success('', $this->newObject->get(['id', 'name', 'description', 'category', 'locked']));
    }


    public function afterSave()
    {
        if ($this->getProperty('clearCache')) {
            $this->modx->cacheManager->refresh();
        }

        return parent::afterSave();
    }
}
