<?php
/**
 * Removes a content type
 *
 * @param integer $id The ID of the content type
 *
 * @package modx
 * @subpackage processors.system.contenttype
 */
class modContentTypeRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modContentType';
    public $languageTopics = array('content_type');
    public $permission = 'content_types';
    public $objectType = 'content_type';

    public function beforeRemove() {
        if ($this->isInUse()) {
            return $this->modx->lexicon('content_type_err_in_use');
        }
        return true;
    }

    public function isInUse() {
        return $this->modx->getCount('modResource',array('content_type' => $this->object->get('id'))) > 0;
    }
}
return 'modContentTypeRemoveProcessor';