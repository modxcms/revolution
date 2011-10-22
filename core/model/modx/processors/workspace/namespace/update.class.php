<?php
/**
 * Updates a namespace from a grid
 *
 * @param string $name A valid name
 * @param string $path An absolute path
 *
 * @package modx
 * @subpackage processors.workspace.namespace
 */
class modNamespaceUpdateProcessor extends modProcessor {
    /** @var modNamespace $namespace */
    public $namespace;

    public function checkPermissions() {
        return $this->modx->hasPermission('namespaces');
    }
    public function getLanguageTopics() {
        return array('workspace','namespace','lexicon');
    }

    public function initialize() {
        $name = $this->getProperty('name');
        if (empty($name)) return $this->modx->lexicon('namespace_err_ns');
        $this->namespace = $this->modx->getObject('modNamespace',$name);
        if (empty($this->namespace)) return $this->modx->lexicon('namespace_err_nf');
        return true;
    }

    public function process() {
        $fields = $this->getProperties();

        if (!$this->validate($fields)) {
            return $this->failure();
        }

        $this->namespace->fromArray($fields,'',true,true);
        $this->namespace->set('path',trim($fields['path']));

        if ($this->namespace->save() === false) {
            return $this->failure($this->modx->lexicon('namespace_err_save'));
        }

        $this->logManagerAction();

        return $this->success('',$this->namespace);
    }

    /**
     * @param array $fields
     * @return boolean
     */
    public function validate(array $fields) {
        if (isset($fields['name']) && empty($fields['name'])) {
            $this->addFieldError('name',$this->modx->lexicon('namespace_err_ns_name'));
        }

        return !$this->hasErrors();
    }

    /**
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('namespace_update','modNamespace',$this->namespace->get('name'));
    }
}
return 'modNamespaceUpdateProcessor';