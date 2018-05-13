<?php

namespace MODX\Processors\Workspace\Lexicon;

use MODX\modLexiconEntry;
use MODX\Processors\modProcessor;

/**
 * Updates a lexicon entry from a grid
 *
 * @package modx
 * @subpackage processors.workspace.lexicon
 */
class UpdateFromGrid extends modProcessor
{
    /** @var modLexiconEntry $entry */
    public $entry;


    public function checkPermissions()
    {
        return $this->modx->hasPermission('lexicons');
    }


    public function getLanguageTopics()
    {
        return ['lexicon'];
    }


    public function initialize()
    {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $data = json_decode($data, true);
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $this->setProperties($data);
        $this->unsetProperty('data');

        return parent::initialize();
    }


    public function process()
    {
        $language = $this->getProperty('language');
        $namespace = $this->getProperty('namespace');
        $topic = $this->getProperty('topic');
        $name = $this->getProperty('name');
        $value = $this->getProperty('value');

        $entries = $this->modx->lexicon->getFileTopic($language, $namespace, $topic);

        /* get entry */
        $this->entry = $this->modx->getObject('modLexiconEntry', [
            'name' => $name,
            'namespace' => $namespace,
            'language' => $language,
            'topic' => $topic,
        ]);
        /* if entry is same as file, remove db custom */
        if (!empty($entries[$name]) && $entries[$name] == $value) {
            if ($this->entry) {
                $this->entry->remove();
                $this->entry->clearCache();
            }
        } else {
            if ($this->entry == null) {
                $this->entry = $this->modx->newObject('modLexiconEntry');
                $this->entry->set('name', $name);
                $this->entry->set('namespace', $namespace);
                $this->entry->set('language', $language);
                $this->entry->set('topic', $topic);
            }
            $this->entry->set('editedon', date('Y-m-d H:i:s'));
            $this->entry->set('value', $value);

            if (!$this->entry->save()) {
                return $this->failure($this->modx->lexicon('entry_err_save'));
            }

            /* clear cache */
            $this->entry->clearCache();
        }

        $this->logManagerAction();

        return $this->success();
    }


    public function logManagerAction()
    {
        $this->modx->logManagerAction('lexicon_entry_update', 'modLexiconEntry', $this->entry->get('id'));
    }
}