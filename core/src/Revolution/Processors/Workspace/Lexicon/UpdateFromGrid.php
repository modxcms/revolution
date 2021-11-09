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
class UpdateFromGrid extends Processor
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
     * @return bool|string|null
     * @throws \xPDO\xPDOException
     */
    public function initialize()
    {
        $data = $this->getProperty('data');
        if (empty($data)) {
            return $this->modx->lexicon('invalid_data');
        }
        $data = $this->modx->fromJSON($data);
        if (empty($data)) {
            return $this->modx->lexicon('invalid_data');
        }
        foreach ($data as $k => $v) {
            $data[$k] = $k === 'value' ? $v : trim($v);
        }
        $this->setProperties($data);
        $this->unsetProperty('data');

        return parent::initialize();
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $language = $this->getProperty('language');
        $namespace = $this->getProperty('namespace');
        $topic = $this->getProperty('topic');
        $name = $this->getProperty('name');
        $value = $this->getProperty('value');

        $entries = $this->modx->lexicon->getFileTopic($language, $namespace, $topic);

        /* get entry */
        $this->entry = $this->modx->getObject(modLexiconEntry::class, [
            'name' => $name,
            'namespace' => $namespace,
            'language' => $language,
            'topic' => $topic
        ]);
        /* if entry is same as file, remove db custom */
        if (!empty($entries[$name]) && $entries[$name] === $value) {
            if ($this->entry) {
                $this->entry->remove();
                $this->entry->clearCache();
            }
        } else {
            if ($this->entry === null) {
                $this->entry = $this->modx->newObject(modLexiconEntry::class);
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
        $this->modx->logManagerAction('lexicon_entry_update', modLexiconEntry::class, $this->entry->get('id'));
    }
}
