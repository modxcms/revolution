<?php
/**
 * Grabs a context
 *
 * @param string $key The key of the context
 *
 * @package modx
 * @subpackage processors.context
 */
class modContextGetProcessor extends modObjectGetProcessor {
    public $classKey = 'modContext';
    public $languageTopics = array('context');
    public $permission = 'view_context';
    public $objectType = 'context';
    public $primaryKeyField = 'key';
    
    public function initialize() {
        $key = $this->getProperty('key');
        $this->setProperty('key',urldecode($key));
        return parent::initialize();
    }
}
return 'modContextGetProcessor';
