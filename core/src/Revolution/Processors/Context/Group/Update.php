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
use MODX\Revolution\Processors\Model\UpdateProcessor;

/**
 * Update a Context Group.
 *
 * @property integer $id
 * @property string  $name
 * @property string  $description
 * @property integer $rank
 *
 * @package MODX\Revolution\Processors\Context\Group
 */
class Update extends UpdateProcessor
{
    public $classKey = modContextGroup::class;
    public $languageTopics = ['context'];
    public $permission = 'edit_context';
    public $objectType = 'context_group';

    public function beforeSave()
    {
        $name = trim((string)$this->getProperty('name', $this->object->get('name')));
        if ($name === '') {
            $this->addFieldError('name', $this->modx->lexicon('context_group_err_ns_name'));
        } else {
            $existing = $this->modx->getObject(modContextGroup::class, [
                'name' => $name,
                'id:!=' => $this->object->get('id'),
            ]);
            if ($existing) {
                $this->addFieldError('name', $this->modx->lexicon('context_group_err_ae'));
            }
        }
        $this->object->set('name', $name);
        if ($this->getProperty('description') !== null) {
            $this->object->set('description', (string)$this->getProperty('description'));
        }
        if ($this->getProperty('rank') !== null) {
            $this->object->set('rank', (int)$this->getProperty('rank'));
        }

        return !$this->hasErrors();
    }
}
