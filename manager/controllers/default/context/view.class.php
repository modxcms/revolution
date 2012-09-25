<?php
/**
 * Loads the view context preview page.
 *
 * @package modx
 * @subpackage manager.controllers
 */
class ContextViewManagerController extends modManagerController {
    public $contextKey = '';
    
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('view_context');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = array()) {
        /* get context by key */
        $context= $this->modx->getObjectGraph('modContext', '{"ContextSettings":{}}', $scriptProperties['key']);
        if ($context == null) {
            return $this->failure($this->modx->lexicon('context_with_key_not_found',array('key' =>  $scriptProperties['key'])));
        }
        if (!$context->checkPolicy('view')) return $this->failure($this->modx->lexicon('permission_denied'));
        
        /* prepare context data for display */
        if (!$context->prepare()) {
            return $this->failure($this->modx->lexicon('context_err_load_data'), $context->toArray());
        }
        
        /* assign context and display */
        $placeholders = array();
        $placeholders['context'] = $context;
        $placeholders['_ctx'] = $context->get('key');
        $this->contextKey = $context->get('key');
        return $this->modx->smarty->fetch('context/view.tpl');
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('context').': '.$this->contextKey;
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'context/view.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('context');
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Contexts';
    }
}