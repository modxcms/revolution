<?php
/**
 * Removes namespaces.
 *
 * @param string $name The name of the namespace.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.workspace.namespace
 */
class modNamespaceRemoveMultipleProcessor extends modProcessor {
    /** @var modNamespace $namespace */
    public $namespace;

    public function checkPermissions() {
        return $this->modx->hasPermission('namespaces');
    }
    public function getLanguageTopics() {
        return array('workspace','namespace','lexicon');
    }

    public function initialize() {
        $namespaces = $this->getProperty('namespaces');
        if (empty($namespaces)) return $this->modx->lexicon('namespace_err_ns');
        return true;
    }

    public function process() {
        $namespaceIds = explode(',',$this->getProperty('namespaces'));

        if (!empty($namespaceIds)) {
            foreach ($namespaceIds as $namespaceId) {
                /** @var modNamespace $namespace */
                $namespace = $this->modx->getObject('modNamespace',$namespaceId);
                if (empty($namespace)) { continue; }

                if ($namespace->get('name') == 'core') continue;

                if ($namespace->remove() == false) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR,$this->modx->lexicon('namespace_err_remove'));
                    continue;
                }

                /* log manager action */
                $this->modx->logManagerAction('namespace_remove','modNamespace',$namespace->get('name'));
            }
        }
        return $this->success();
    }

    /**
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('namespace_remove','modNamespace',$this->namespace->get('name'));
    }
}
return 'modNamespaceRemoveMultipleProcessor';