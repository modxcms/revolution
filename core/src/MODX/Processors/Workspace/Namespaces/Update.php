<?php

namespace MODX\Processors\Workspace\Namespaces;

use MODX\Processors\modObjectUpdateProcessor;

/**
 * Updates a namespace from a grid
 *
 * @param string $name A valid name
 * @param string $path An absolute path
 *
 * @package modx
 * @subpackage processors.workspace.namespace
 */
class Update extends modObjectUpdateProcessor
{
    public $classKey = 'modNamespace';
    public $languageTopics = ['workspace', 'namespace', 'lexicon'];
    public $permission = 'namespaces';
    public $objectType = 'namespace';
    public $primaryKeyField = 'name';


    public function beforeSave()
    {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('namespace_err_ns_name'));
        }
        $this->object->set('name', $name);

        $this->object->set('path', trim($this->object->get('path')));
        $this->object->set('assets_path', trim($this->object->get('assets_path')));

        return parent::beforeSave();
    }
}