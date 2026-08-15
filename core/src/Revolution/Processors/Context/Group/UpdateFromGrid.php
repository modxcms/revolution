<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Context\Group;

use MODX\Revolution\modContextGroup;
use MODX\Revolution\Processors\Processor;

/**
 * Update a Context Group from a grid row (JSON data).
 *
 * @package MODX\Revolution\Processors\Context\Group
 */
class UpdateFromGrid extends Processor
{
    /** @var modContextGroup */
    public $object;

    public function checkPermissions()
    {
        return $this->modx->hasPermission('edit_context');
    }

    public function getLanguageTopics()
    {
        return ['context'];
    }

    public function initialize()
    {
        $data = $this->getProperty('data');
        if (empty($data)) {
            return $this->modx->lexicon('context_group_err_ns');
        }

        $record = $this->modx->fromJSON($data);
        if (empty($record['id'])) {
            return $this->modx->lexicon('context_group_err_ns');
        }

        $this->object = $this->modx->getObject(modContextGroup::class, (int)$record['id']);
        if (!$this->object) {
            return $this->modx->lexicon('context_group_err_nf');
        }

        $this->setProperties($record);

        return true;
    }

    public function process()
    {
        $name = trim((string)$this->getProperty('name', ''));
        if ($name === '') {
            return $this->failure($this->modx->lexicon('context_group_err_ns_name'));
        }

        $existing = $this->modx->getObject(modContextGroup::class, [
            'name' => $name,
            'id:!=' => $this->object->get('id'),
        ]);
        if ($existing) {
            return $this->failure($this->modx->lexicon('context_group_err_ae'));
        }

        $this->object->fromArray([
            'name' => $name,
            'description' => (string)$this->getProperty('description', ''),
            'rank' => (int)$this->getProperty('rank', 0),
        ]);

        if ($this->object->save() === false) {
            return $this->failure($this->modx->lexicon('context_group_err_save'));
        }

        return $this->success('', $this->object);
    }
}
