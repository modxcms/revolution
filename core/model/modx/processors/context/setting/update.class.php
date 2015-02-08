<?php
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

include_once MODX_CORE_PATH . 'model/modx/processors/system/settings/update.class.php';
class modContextSettingUpdateProcessor extends modSystemSettingsUpdateProcessor {

    public $classKey = 'modContextSetting';

    /** @var modContext */
    public $context;
    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
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

        $this->object = $this->modx->getObject($this->classKey, array(
            'key' => $key,
            'context_key' => $context_key,
        ));

        if (!$this->object) {
            return $this->modx->lexicon($this->objectType . '_err_nf');
        }

        return true;
    }


    /**
     * If friendly_urls is set on or use_alias_path changes, refreshURIs
     * @return boolean
     */
    public function refreshURIs() {
        if ($this->refreshURIs) {
            $this->context->config[$this->object->get('key')] = $this->object->get('value');
            $this->modx->call('modResource', 'refreshURIs', array(&$this->modx, 0, array('contexts' => $this->context->get('key'))));
        }
        return $this->refreshURIs;
    }

    /**
     * Clear the context settings cache
     * @return void
     */
    public function clearCache() {
        $this->modx->cacheManager->refresh(array(
            'db' => array(),
            'context_settings' => array('contexts' => array($this->context->get('key'))),
            'resource' => array('contexts' => array($this->context->get('key'))),
        ));
    }
}

return 'modContextSettingUpdateProcessor';
