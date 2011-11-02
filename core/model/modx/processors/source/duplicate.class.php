<?php
/**
 * Duplicates a source.
 *
 * @param integer $id The source to duplicate
 * @param string $name The name of the new source.
 * 
 * @package modx
 * @subpackage processors.source
 */
class modSourceDuplicateProcessor extends modObjectDuplicateProcessor {
    public $classKey = 'sources.modMediaSource';
    public $languageTopics = array('source');
    public $permission = 'source_save';
    public $objectType = 'source';
    public $checkSavePermission = false;

    public function initialize() {
        $initialized = parent::initialize();
        if (!$this->object->checkPolicy('copy')) return $this->modx->lexicon('access_denied');
        return $initialized;
    }
}
return 'modSourceDuplicateProcessor';