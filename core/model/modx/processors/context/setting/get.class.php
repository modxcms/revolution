<?php

/**
 * Gets a context setting
 *
 * @param string $context_key The key of the context
 * @param string $key The key of the setting
 *
 * @package modx
 * @subpackage processors.context.setting
 */
class modContextSettingGetProcessor extends modObjectGetProcessor {
    public $classKey = 'modContextSetting';
    public $languageTopics = array('setting');
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

        $this->object = $this->modx->getObject($this->classKey, array(
            'key' => $key,
            'context_key' => $context_key,
        ));

        if (!$this->object) {
            return $this->modx->lexicon($this->objectType . '_err_nf');
        }

        return true;
    }
}

return 'modContextSettingGetProcessor';