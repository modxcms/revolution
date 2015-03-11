<?php
/**
 * Removes a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */

class modPropertySetRemoveProcessor extends modObjectRemoveProcessor {
    public $objectType = 'propertyset';
    public $classKey = 'modPropertySet';
    public $permission = 'delete_propertyset';
    public $languageTopics = array('propertyset');
}

return 'modPropertySetRemoveProcessor';
