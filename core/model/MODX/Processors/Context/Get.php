<?php

namespace MODX\Processors\Context;

use MODX\Processors\modObjectGetProcessor;

/**
 * Grabs a context
 *
 * @param string $key The key of the context
 *
 * @package modx
 * @subpackage processors.context
 */
class Get extends modObjectGetProcessor
{
    public $classKey = 'modContext';
    public $languageTopics = ['context'];
    public $permission = 'view_context';
    public $objectType = 'context';
    public $primaryKeyField = 'key';


    public function initialize()
    {
        $key = $this->getProperty('key');
        $this->setProperty('key', urldecode($key));

        return parent::initialize();
    }
}
