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
class Create extends Processor
{
    /** @var modLexiconEntry $entry */
    public $entry;

    /**
     * @return mixed
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
     * @return mixed
     */
    public function process()
    {
        $data = [];
        foreach ($this->getProperties() as $k => $v) {
            $data[$k] = $k === 'value' ? $v : trim($v);
        }

        if ($this->alreadyExists($data)) {
            return $this->failure($this->modx->lexicon('entry_err_ae'));
        }

        $this->entry = $this->modx->newObject(modLexiconEntry::class);
        $this->entry->fromArray($data);
        $this->entry->set('editedon', date('Y-m-d h:i:s'));

        if ($this->entry->save() === false) {
            return $this->failure($this->modx->lexicon('entry_err_save'));
        }

        return $this->success();
    }

    /**
     * @return bool
     */
    public function alreadyExists($data)
    {
        return $this->modx->getCount(modLexiconEntry::class, [
                'name' => $data['name'],
                'namespace' => $data['namespace'],
                'language' => $data['language'],
                'topic' => $data['topic']
            ]) > 0;
    }

    public function logManagerAction()
    {
        $this->modx->logManagerAction('lexicon_entry_create', modLexiconEntry::class, $this->entry->get('id'));
    }
}
