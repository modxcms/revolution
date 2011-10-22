<?php
/**
 * Removes a context
 *
 * @param string $key The key of the context. Cannot be mgr or web.
 *
 * @package modx
 * @subpackage processors.context
 */
class modContextRemoveProcessor extends modProcessor {
    /** @var modContext $context */
    public $context;
    
    public function checkPermissions() {
        return $this->modx->hasPermission('delete_context');
    }
    
    public function getLanguageTopics() {
        return array('context');
    }

    /**
     * {@inheritDoc}
     * 
     * @return mixed
     */
    public function initialize() {
        $key = $this->getProperty('key');
        if (empty($key)) {
            return $this->modx->lexicon('context_err_ns');
        }
        $this->context = $this->modx->getObject('modContext',$key);
        if (empty($this->context)) {
            return $this->modx->lexicon('context_err_nf');
        }
        return true;
    }

    /**
     * {@inheritDoc}
     * 
     * @return array|string
     */
    public function process() {
        /* prevent removing of mgr/web contexts */
        if ($this->context->get('key') == 'web' || $this->context->get('key') == 'mgr') {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }

        /* remove context */
        if ($this->context->remove() == false) {
            return $this->failure($this->modx->lexicon('context_err_remove'));
        }

        $this->removeResources();
        $this->logManagerAction();

        /* clear cache */
        $this->modx->cacheManager->refresh();

        return $this->success();
    }

    /**
     * Remove all the Resources in this Context
     * @return void
     */
    public function removeResources() {
        $this->modx->removeCollection('modResource',array(
            'context_key' => $this->context->get('key'),
        ));
    }

    /**
     * Log the action of removing the Context
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('context_delete','modContext',$this->context->get('key'));
    }
}
return 'modContextRemoveProcessor';