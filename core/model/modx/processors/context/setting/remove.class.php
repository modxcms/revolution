<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Removes a context setting.
 *
 * @param string $key The key of the setting
 * @param string $context_key The key of the context
 *
 * @package modx
 * @subpackage processors.context.setting
 */

include_once MODX_CORE_PATH . 'model/modx/processors/system/settings/remove.class.php';
class modContextSettingRemoveProcessor extends modSystemSettingsRemoveProcessor {
    public $classKey = 'modContextSetting';
    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
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
        if (!$context->checkPolicy('remove')) {
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

return 'modContextSettingRemoveProcessor';
