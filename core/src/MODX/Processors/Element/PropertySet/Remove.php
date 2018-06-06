<?php

namespace MODX\Processors\Element\PropertySet;

use MODX\Processors\modObjectRemoveProcessor;

/**
 * Removes a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
class Remove extends modObjectRemoveProcessor
{
    public $objectType = 'propertyset';
    public $classKey = 'modPropertySet';
    public $permission = 'delete_propertyset';
    public $languageTopics = ['propertyset'];
}
