<?php
/**
 * Removes a namespace.
 *
 * @param string $name The name of the namespace.
 *
 * @package modx
 * @subpackage processors.workspace.namespace
 */
class modNamespaceRemoveProcessor extends modProcessor {
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
        if ($this->namespace->remove() === false) {
            return $this->failure($this->modx->lexicon('namespace_err_remove'));
        }

        $this->logManagerAction();

        return $this->success('',$this->namespace);
    }

    /**
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('namespace_remove','modNamespace',$this->namespace->get('name'));
    }
}
return 'modNamespaceRemoveProcessor';