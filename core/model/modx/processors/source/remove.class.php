<?php
/**
 * Removes a Media Source
 *
 * @param integer $id The ID of the source
 *
 * @package modx
 * @subpackage processors.source
 */
class modSourceRemoveProcessor extends modProcessor {
    /** @var modMediaSource $source */
    public $source;

    public function checkPermissions() {
        return $this->modx->hasPermission('source_delete');
    }

    public function getLanguageTopics() {
        return array('source');
    }
    
    public function initialize() {
        $id = $this->getProperty('id');
        if (empty($id)) return $this->modx->lexicon('source_err_ns');
        /** @var modMediaSource $source */
        $this->source = $this->modx->getObject('sources.modMediaSource',$id);
        if (empty($this->source)) {
            return $this->modx->lexicon('source_err_nf',array('id' => $id));
        }
        if (!$this->source->checkPolicy('remove')) {
            return $this->modx->lexicon('permission_denied');
        }

        return true;
    }
    
    public function process() {
        if ($this->source->get('id') == 1) return $this->failure($this->modx->lexicon('source_err_remove_default'));

        /* remove source */
        if ($this->source->remove() == false) {
            return $this->failure($this->modx->lexicon('source_err_remove'));
        }

        $this->logManagerAction();
        return $this->success('',$this->source);
    }

    /**
     * Log a manager action
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('source_delete','sources.modMediaSource',$this->source->get('id'));
    }
}
return 'modSourceRemoveProcessor';