<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Context;


use MODX\Revolution\modContext;
use MODX\Revolution\Processors\Model\GetProcessor;

/**
 * Grabs a context
 *
 * @property string $key The key of the context
 *
 * @package MODX\Revolution\Processors\Context
 */
class Get extends GetProcessor
{
    public $classKey = modContext::class;
    public $languageTopics = ['context'];
    public $permission = 'view_context';
    public $objectType = 'context';
    public $primaryKeyField = 'key';

    public function initialize()
    {
        $key = $this->getProperty('key');
        $this->setProperty('key', urldecode($key));

        return parent::initialize();
    }
}
