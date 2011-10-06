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
class modNamespaceCreateProcessor extends modProcessor {
    /** @var modNamespace $namespace */
    public $namespace;
    
    public function checkPermissions() {
        return $this->modx->hasPermission('namespaces');
    }
    public function getLanguageTopics() {
        return array('workspace','namespace','lexicon');
    }

    public function initialize() {
        $this->namespace = $this->modx->newObject('modNamespace');
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
            return $this->failure($this->modx->lexicon('namespace_err_create'));
        }

        $this->logManagerAction();

        return $this->success('',$this->namespace);
    }

    /**
     * @param array $fields
     * @return boolean
     */
    public function validate(array $fields) {
        if (empty($fields['name'])) {
            $this->addFieldError('name',$this->modx->lexicon('namespace_err_ns_name'));
        }

        return !$this->hasErrors();
    }

    /**
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('namespace_create','modNamespace',$this->namespace->get('name'));
    }
}
return 'modNamespaceCreateProcessor';