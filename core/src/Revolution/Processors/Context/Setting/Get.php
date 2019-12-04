<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Context\Setting;


use MODX\Revolution\modContext;
use MODX\Revolution\modContextSetting;
use MODX\Revolution\Processors\Model\GetProcessor;

/**
 * Gets a context setting
 *
 * @property string $context_key The key of the context
 * @property string $key         The key of the setting
 *
 * @package MODX\Revolution\Processors\Context\Setting
 */
class Get extends GetProcessor
{
    public $classKey = modContextSetting::class;
    public $languageTopics = ['setting'];
    public $permission = 'settings';
    public $objectType = 'setting';
    public $primaryKeyField = 'key';

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $key = $this->getProperty('key');
        $context_key = $this->getProperty('context_key');
        if (!$key || !$context_key) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }

        /** @var modContext $context */
        $context = $this->modx->getContext($context_key);
        if (!$context) {
            return $this->modx->lexicon($this->objectType . '_err_nf');
        }
        if (!$context->checkPolicy('view')) {
            return $this->modx->lexicon('access_denied');
        }

        $this->object = $this->modx->getObject($this->classKey, [
            'key' => $key,
            'context_key' => $context_key,
        ]);

        if (!$this->object) {
            return $this->modx->lexicon($this->objectType . '_err_nf');
        }

        return true;
    }
}
