<?php
/**
 * Creates a Media Source
 *
 * @package modx
 * @subpackage processors.source
 */
class modSourceCreateProcessor extends modProcessor {
    /** @var modMediaSource $source */
    public $source;

    public function checkPermissions() {
        return $this->modx->hasPermission('source_save');
    }

    public function getLanguageTopics() {
        return array('source');
    }

    public function process() {
        if (!$this->validate()) {
            return $this->failure();
        }
        
        $this->source = $this->modx->newObject('sources.modMediaSource');
        $this->source->fromArray($this->getProperties());
        
        if (!$this->source->checkPolicy('save')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        
        /* save source */
        if ($this->source->save() == false) {
            return $this->failure($this->modx->lexicon('source_err_save'));
        } else {
            /* log manager action */
            $this->modx->logManagerAction('source_create','sources.modMediaSource',$this->source->get('id'));
        }
        
        return $this->success('',$this->source);
    }

    /**
     * Validate the properties sent
     * 
     * @return boolean
     */
    public function validate() {
        /* validate name field */
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('source_err_ns_name'));
        } else if ($this->alreadyExists($name)) {
            $this->addFieldError('name',$this->modx->lexicon('source_err_ae_name',array(
                'name' => $name,
            )));
        }

        return !$this->hasErrors();
    }

    /**
     * Check to see if a Media Source with the specified name already exists
     * @param string $name
     * @return boolean
     */
    public function alreadyExists($name) {
        return $this->modx->getCount('sources.modMediaSource',array(
            'name' => $name,
        )) > 0;
    }
}