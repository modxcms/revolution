<?php
/**
 * Removes a context
 *
 * @param string $key The key of the context. Cannot be mgr or web.
 *
 * @package modx
 * @subpackage processors.context
 */
class modContextRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modContext';
    public $languageTopics = array('context');
    public $permission = 'delete_context';
    public $objectType = 'context';
    public $primaryKeyField = 'key';

    public function beforeRemove() {
        /* prevent removing of mgr/web contexts */
        if ($this->object->get('key') == 'web' || $this->object->get('key') == 'mgr') {
            return $this->modx->lexicon('permission_denied');
        }
        return true;
    }

    public function afterRemove() {
        $this->modx->removeCollection('modResource',array(
            'context_key' => $this->object->get('key'),
        ));
        return true;
    }

    public function cleanup() {
        $this->modx->cacheManager->refresh();
    }
}
return 'modContextRemoveProcessor';