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
class modSourceDuplicateProcessor extends modProcessor {
    /** @var modMediaSource $oldSource */
    public $oldSource;
    /** @var modMediaSource $newSource */
    public $newSource;

    public function checkPermissions() {
        return $this->modx->hasPermission('source_save');
    }

    public function getLanguageTopics() {
        return array('source');
    }

    public function initialize() {
        $id = $this->getProperty('id');
        if (empty($id)) return $this->modx->lexicon('source_err_ns');
        
        $this->oldSource = $this->modx->getObject('sources.modMediaSource',$id);
        if (empty($this->oldSource)) return $this->modx->lexicon('source_err_nf');

        if (!$this->oldSource->checkPolicy('copy')) return $this->modx->lexicon('access_denied');
        return true;
    }

    public function process() {
        $fields = $this->getProperties();

        /* check name */
        $newName = !empty($fields['name'])
            ? $fields['name']
            : $this->modx->lexicon('duplicate_of',array(
                'name' => $this->oldSource->get('name'),
            ));

        /* @var modMediaSource $newSource */
        $this->newSource = $this->modx->newObject('sources.modMediaSource');
        $this->newSource->fromArray($this->oldSource->toArray());
        $this->newSource->set('name',$newName);

        if ($this->newSource->save() === false) {
            $this->modx->error->checkValidation($this->newSource);
            return $this->failure($this->modx->lexicon('source_err_duplicate'));
        }

        $this->logManagerAction();

        return $this->success('',$this->newSource->get(array('id', 'name', 'description')));
    }

    /**
     * Log a manager action for duplicating the source
     * 
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('source_duplicate','sources.modMediaSource',$this->newSource->get('id'));
    }
    
}
return 'modSourceDuplicateProcessor';