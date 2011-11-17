<?php
/**
 * Removes multiple Media Sources
 * 
 * @package modx
 * @subpackage processors.source
 */
class modSourceRemoveMultipleProcessor extends modProcessor {
    /** @var modMediaSource $source */
    public $source;

    public function checkPermissions() {
        return $this->modx->hasPermission('source_delete');
    }

    public function getLanguageTopics() {
        return array('source');
    }
        
    public function process() {
        $sources = $this->getProperty('sources');
        if (empty($sources)) return $this->failure($this->modx->lexicon('source_err_ns'));

        $sourceIds = explode(',',$sources);
        foreach ($sourceIds as $sourceId) {
            /** @var modMediaSource $source */
            $source = $this->modx->getObject('sources.modMediaSource',$sourceId);
            if (empty($source)) { continue; }

            if ($source->get('id') == 1) continue;
            if (!$source->checkPolicy('remove')) {
                continue;
            }

            if ($source->remove() == false) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,$this->modx->lexicon('source_err_remove'));
                continue;
            }
            $this->logManagerAction();
        }

        return $this->success();
    }
    
    /**
     * Log a manager action
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('source_delete','sources.modMediaSource',$this->source->get('id'));
    }
}
return 'modSourceRemoveMultipleProcessor';