<?php
/**
 * Grabs a context
 *
 * @param string $key The key of the context
 *
 * @package modx
 * @subpackage processors.context
 */
class modContextGetProcessor extends modProcessor {
    /** @var modContext $context */
    public $context;
    
    public function checkPermissions() {
        return $this->modx->hasPermission('view_context');
    }
    public function getLanguageTopics() {
        return array('context');
    }

    public function initialize() {
        $key = $this->getProperty('key');
        if (empty($key)) { return $this->modx->lexicon('context_err_ns'); }
        $contextKey = urldecode($key);
        
        $this->context = $this->modx->getObject('modContext',$contextKey);
        if (empty($this->context)) {
            return $this->modx->lexicon('context_err_nfs',array('key' => $key));
        }
        if (!$this->context->checkPolicy('view')) {
            return $this->modx->lexicon('permission_denied');
        }
        return true;
    }

    public function process() {
        return $this->success('',$this->context);
    }
}
return 'modContextGetProcessor';
