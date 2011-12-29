<?php
/**
 * Removes a Media Source
 *
 * @param integer $id The ID of the source
 *
 * @package modx
 * @subpackage processors.source
 */
class modSourceRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'sources.modMediaSource';
    public $languageTopics = array('source');
    public $permission = 'source_delete';
    public $objectType = 'source';
    public $beforeRemoveEvent = 'OnMediaSourceBeforeFormDelete';
    public $afterRemoveEvent = 'OnMediaSourceFormDelete';

    public function beforeRemove() {
        if ($this->object->get('id') == 1) return $this->modx->lexicon('source_err_remove_default');
        return true;
    }
}
return 'modSourceRemoveProcessor';