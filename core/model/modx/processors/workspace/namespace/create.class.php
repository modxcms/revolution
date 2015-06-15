<?php
/**
 * Creates a namespace
 *
 * @param string $name The name of the namespace
 * @param string $path (optional) The path of the namespace
 *
 * @package modx
 * @subpackage processors.workspace.namespace
 */
class modNamespaceCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'modNamespace';
    public $languageTopics = array('namespace');
    public $permission = 'namespaces';
    public $objectType = 'namespace';
    public $primaryKeyField = 'name';

    public function beforeSave() {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon($this->objectType.'_err_ns'));
        }
        $this->object->set('name', $name);

        $this->object->set('path', trim($this->object->get('path')));
        $this->object->set('assets_path', trim($this->object->get('assets_path')));
        return parent::beforeSave();
    }
}

return 'modNamespaceCreateProcessor';
