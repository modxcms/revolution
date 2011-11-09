<?php
/**
 * Updates a lexicon entry from a grid
 *
 * @package modx
 * @subpackage processors.workspace.lexicon
 */
class modLexiconEntryCreateProcessor extends modProcessor {
    /** @var modLexiconEntry $entry */
    public $entry;

    public function checkPermissions() {
        return $this->modx->hasPermission('lexicons');
    }
    public function getLanguageTopics() {
        return array('lexicon');
    }

    public function process() {
        if ($this->alreadyExists()) {
            return $this->failure($this->modx->lexicon('entry_err_ae'));
        }

        $this->entry = $this->modx->newObject('modLexiconEntry');
        $this->entry->fromArray($this->getProperties());
        $this->entry->set('editedon',date('Y-m-d h:i:s'));

        if ($this->entry->save() == false) {
            return $this->failure($this->modx->lexicon('entry_err_save'));
        }

        return $this->success();
    }

    public function alreadyExists() {
        return $this->modx->getCount('modLexiconEntry',array(
            'name' => $this->getProperty('name'),
            'namespace' => $this->getProperty('namespace'),
            'language' => $this->getProperty('language'),
            'topic' => $this->getProperty('topic'),
        )) > 0;
    }

    public function logManagerAction() {
        $this->modx->logManagerAction('lexicon_entry_create','modLexiconEntry',$this->entry->get('id'));
    }
}
return 'modLexiconEntryCreateProcessor';