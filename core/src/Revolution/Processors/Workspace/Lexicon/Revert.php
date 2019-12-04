<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Workspace\Lexicon;

use MODX\Revolution\modLexiconEntry;
use MODX\Revolution\Processors\Processor;

/**
 * Updates a lexicon entry from a grid
 * @package MODX\Revolution\Processors\Workspace\Lexicon
 */
class Revert extends Processor
{
    /** @var modLexiconEntry $entry */
    public $entry;

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('lexicons');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['lexicon'];
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $language = $this->getProperty('language', 'en');
        $namespace = $this->getProperty('namespace', 'core');
        $topic = $this->getProperty('topic', 'default');

        $this->modx->lexicon->getFileTopic($language, $namespace, $topic);

        /* @var modLexiconEntry $entry */
        $this->entry = $this->modx->getObject(modLexiconEntry::class, [
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
        $this->modx->logManagerAction('lexicon_entry_update', modLexiconEntry::class, $this->entry->get('id'));
    }
}
