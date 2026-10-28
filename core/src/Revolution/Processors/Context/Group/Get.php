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
use MODX\Revolution\Processors\Model\GetProcessor;

/**
 * Get a Context Group.
 *
 * @property integer $id
 *
 * @package MODX\Revolution\Processors\Context\Group
 */
class Get extends GetProcessor
{
    public $classKey = modContextGroup::class;
    public $languageTopics = ['context'];
    public $permission = 'view_context';
    public $objectType = 'context_group';
}
