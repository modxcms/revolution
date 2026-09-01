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
use MODX\Revolution\Processors\Model\RemoveProcessor;

/**
 * Remove a Context Group. Member Contexts are unassigned by modContextGroup::remove().
 *
 * @property integer $id
 *
 * @package MODX\Revolution\Processors\Context\Group
 */
class Remove extends RemoveProcessor
{
    public $classKey = modContextGroup::class;
    public $languageTopics = ['context'];
    public $permission = 'delete_context';
    public $objectType = 'context_group';

    public function afterRemove()
    {
        if ($this->modx->getCacheManager()) {
            $this->modx->cacheManager->flushPermissions();
        }

        return parent::afterRemove();
    }
}
