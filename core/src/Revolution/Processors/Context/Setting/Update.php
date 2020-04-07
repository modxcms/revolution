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
use MODX\Revolution\modResource;

/**
 * Updates a context setting
 *
 * @property string $context_key The key of the context
 * @property string $key         The key of the setting
 * @property string $value       The value of the setting.
 *
 * @package MODX\Revolution\Processors\Context\Setting
 */
class Update extends \MODX\Revolution\Processors\System\Settings\Update
{
    public $classKey = modContextSetting::class;

    /** @var modContext */
    public $context;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $key = $this->getProperty('key');
        $context_key = $this->getProperty('context_key', $this->getProperty('fk'));
        if (!$key || !$context_key) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }

        $this->context = $this->modx->getContext($context_key);
        if (!$this->context) {
            return $this->modx->lexicon($this->objectType . '_err_nf');
        }
        if (!$this->context->checkPolicy('save')) {
            return $this->modx->lexicon('permission_denied');
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


    /**
     * If friendly_urls is set on or use_alias_path changes, refreshURIs
     *
     * @return boolean
     */
    public function refreshURIs()
    {
        if ($this->refreshURIs) {
            $this->context->config[$this->object->get('key')] = $this->object->get('value');
            $this->modx->call(modResource::class, 'refreshURIs',
                [&$this->modx, 0, ['contexts' => $this->context->get('key')]]);
        }

        return $this->refreshURIs;
    }

    /**
     * Clear the context settings cache
     *
     * @return void
     */
    public function clearCache()
    {
        $this->modx->cacheManager->refresh([
            'db' => [],
            'context_settings' => ['contexts' => [$this->context->get('key')]],
            'resource' => ['contexts' => [$this->context->get('key')]],
        ]);
    }
}
