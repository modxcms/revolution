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
use MODX\Revolution\Processors\Model\CreateProcessor;

/**
 * Create a Context Group.
 *
 * @property string  $name
 * @property string  $description
 * @property integer $rank
 *
 * @package MODX\Revolution\Processors\Context\Group
 */
class Create extends CreateProcessor
{
    public $classKey = modContextGroup::class;
    public $languageTopics = ['context'];
    public $permission = 'new_context';
    public $objectType = 'context_group';

    public function beforeSave()
    {
        $name = trim((string)$this->getProperty('name', ''));
        if ($name === '') {
            $this->addFieldError('name', $this->modx->lexicon('context_group_err_ns_name'));
        } elseif ($this->doesAlreadyExist(['name' => $name])) {
            $this->addFieldError('name', $this->modx->lexicon('context_group_err_ae'));
        }
        $this->object->set('name', $name);
        $this->object->set('description', (string)$this->getProperty('description', ''));
        $this->object->set('rank', (int)$this->getProperty('rank', 0));

        return !$this->hasErrors();
    }
}
