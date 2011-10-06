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
        $fields = $this->getProperties();

        if (empty($fields['class_key'])) {
            $this->setProperty('class_key','sources.modFileMediaSource');
            $fields['class_key'] = 'sources.modFileMediaSource';
        }

        if (!$this->validate($fields)) {
            return $this->failure();
        }
        
        $this->source = $this->modx->newObject($fields['class_key']);
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
     * @param array $fields
     * @return boolean
     */
    public function validate(array $fields) {
        /* validate name field */
        if (empty($fields['name'])) {
            $this->addFieldError('name',$this->modx->lexicon('source_err_ns_name'));
        } else if ($this->alreadyExists($fields['name'])) {
            $this->addFieldError('name',$this->modx->lexicon('source_err_ae_name',array(
                'name' => $fields['name'],
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
return 'modSourceCreateProcessor';