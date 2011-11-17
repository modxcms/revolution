<?php
/**
 * Update a context from a grid. Passed as JSON data.
 *
 * @package modx
 * @subpackage processors.context
 */
class modContextUpdateFromGridProcessor extends modProcessor {
    /** @var modContext $context */
    public $context;
    
    public function checkPermissions() {
        return $this->modx->hasPermission('edit_context');
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
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('context_err_ns');

        $record = $this->modx->fromJSON($data);

        /* get context */
        if (empty($record['key'])) return $this->modx->error->failure($this->modx->lexicon('context_err_ns'));
        $this->context = $this->modx->getObject('modContext', $record['key']);
        if (empty($this->context)) return $this->modx->lexicon('context_err_nf');

        $this->setProperties($record);
        return true;
    }

    /**
     * {@inheritDoc}
     * 
     * @return mixed
     */
    public function process() {
        if (!$this->validate()) {
            return $this->failure();
        }

        $this->context->fromArray($this->getProperties());
        if ($this->context->save() == false) {
            $this->modx->error->checkValidation($this->context);
            return $this->failure($this->modx->lexicon('context_err_save'));
        }

        $this->runOnUpdateEvent();
        $this->logManagerAction();

        return $this->success('',$this->context);
    }

    /**
     * Validate the passed properties
     *
     * @return boolean
     */
    public function validate() {
        $key = $this->getProperty('key');
        if (empty($key)) {
            $this->addFieldError('key',$this->modx->lexicon('context_err_ns_key'));
        }
        if ($this->context->get('key') != $key) {
            if ($this->alreadyExists($key)) {
                $this->addFieldError('key',$this->modx->lexicon('context_err_ae'));
            }
        }
        return !$this->hasErrors();
    }

    /**
     * Check to see if the context already exists
     *
     * @param string $key
     * @return boolean
     */
    public function alreadyExists($key) {
        return $this->modx->getCount('modContext',$key) > 0;
    }

    /**
     * Run the OnContextUpdate event
     * @return void
     */
    public function runOnUpdateEvent() {
        $this->modx->invokeEvent('OnContextUpdate',array(
            'context' => &$this->context,
            'properties' => $this->getProperties(),
        ));
    }

    /**
     * Log the manager action of updating this Context
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('context_update','modContext',$this->context->get('id'));
    }
}
return 'modContextUpdateFromGridProcessor';