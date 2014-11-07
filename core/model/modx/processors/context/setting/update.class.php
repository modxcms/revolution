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

class modContextSettingUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modContextSetting';
    public $languageTopics = array('setting');
    public $permission = 'settings';
    public $objectType = 'setting';
    public $primaryKeyField = 'key';

    /** @var modContext */
    public $context;
    public $refreshURIs = false;
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

        $this->context = $this->modx->getContext($this->getProperty('context_key'));
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

    public function beforeSet() {
        /* value parsing */
        $xtype = $this->getProperty('xtype');
        $value = $this->getProperty('value');

        if ($xtype == 'combo-boolean' && !is_numeric($value)) {
            if (in_array($value, array('yes', 'Yes', $this->modx->lexicon('yes'), 'true', 'True'))) {
                $this->setProperty('value', 1);
            } else {
                $this->setProperty('value', 0);
            }
        }

        return parent::beforeSet();
    }

    public function beforeSave() {
        $names = array(
            'name' => 'setting_'.$this->getProperty('key'),
            'description' => 'setting_'.$this->getProperty('key').'_desc',
        );

        foreach ($names as $nameKey => $nameVal) {
            $value = $this->getProperty($nameKey);
            if (!empty($field)) {
                $criteria = array(
                    'namespace' => $this->object->get('namespace'),
                    'topic' => 'default',
                    'name' => $nameVal,
                );
                /** @var modLexiconEntry $entry */
                $entry = $this->modx->getObject('modLexiconEntry', $criteria);
                if ($entry === null) {
                    $entry = $this->modx->newObject('modLexiconEntry');
                    $entry->fromArray($criteria);
                }
                $entry->set('value',$value);
                $entry->save();
                $entry->clearCache();
            }
        }

        if ($this->object->get('key') === 'friendly_urls' && $this->object->isDirty('value') && $this->object->get('value') == '1') {
            $this->refreshURIs = true;
        }
        if ($this->object->get('key') === 'use_alias_path' && $this->object->isDirty('value')) {
            $this->refreshURIs = true;
        }
        if ($this->object->get('key') === 'container_suffix' && $this->object->isDirty('value')) {
            $this->refreshURIs = true;
        }

        return parent::beforeSave();
    }


    public function afterSave() {
        /* if friendly_urls is set on or use_alias_path changes, refreshURIs */
        if ($this->refreshURIs) {
            $this->context->config[$this->object->get('key')] = $this->object->get('value');
            $this->modx->call('modResource', 'refreshURIs', array(&$this->modx, 0, array('contexts' => $this->context->get('key'))));
        }

        $this->modx->cacheManager->refresh(array(
            'db' => array(),
            'context_settings' => array('contexts' => array($this->context->get('key'))),
            'resource' => array('contexts' => array($this->context->get('key'))),
        ));

        return parent::afterSave();
    }
}

return 'modContextSettingUpdateProcessor';