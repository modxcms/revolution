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
class Revert extends modProcessor
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


    public function process()
    {
        $language = $this->getProperty('language', 'en');
        $namespace = $this->getProperty('namespace', 'core');
        $topic = $this->getProperty('topic', 'default');

        $this->modx->lexicon->getFileTopic($language, $namespace, $topic);

        /* @var modLexiconEntry $entry */
        $this->entry = $this->modx->getObject('modLexiconEntry', [
            'name' => $this->getProperty('name'),
            'namespace' => $namespace,
            'language' => $language,
            'topic' => $topic,
        ]);
        if ($this->entry) {
            $this->entry->remove();
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