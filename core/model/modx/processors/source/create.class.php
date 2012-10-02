<?php
/**
 * Creates a Media Source
 *
 * @package modx
 * @subpackage processors.source
 */
class modSourceCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'sources.modMediaSource';
    public $languageTopics = array('source');
    public $permission = 'source_save';
    public $objectType = 'source';
    public $beforeSaveEvent = 'OnMediaSourceBeforeFormSave';
    public $afterSaveEvent = 'OnMediaSourceFormSave';

    public function initialize() {
        $classKey = $this->getProperty('class_key');
        if (empty($classKey)) {
            $this->setProperty('class_key','sources.modFileMediaSource');
        }
        return parent::initialize();
    }

    /**
     * Validate the properties sent
     * @return boolean
     */
    public function beforeSave() {
        /* validate name field */
        $name = $this->object->get('name');
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
return 'modSourceCreateProcessor';