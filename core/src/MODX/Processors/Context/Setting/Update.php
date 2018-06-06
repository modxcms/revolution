<?php

namespace MODX\Processors\Context\Setting;

use MODX\modContext;

/**
 * Updates a context setting
 *
 * @param string $context_key The key of the context
 * @param string $key The key of the setting
 * @param string $value The value of the setting.
 *
 * @package modx
 * @subpackage processors.context.setting
 */
class Update extends \MODX\Processors\System\Settings\Update
{

    public $classKey = 'modContextSetting';

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
            $this->modx->call('modResource', 'refreshURIs', [&$this->modx, 0, ['contexts' => $this->context->get('key')]]);
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
