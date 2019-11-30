<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Settings;

use MODX\Revolution\modLexiconEntry;
use MODX\Revolution\modNamespace;
use MODX\Revolution\Processors\Model\CreateProcessor;
use MODX\Revolution\modSystemSetting;

/**
 * Create a system setting
 * @param string $key The key to create
 * @param string $value The value of the setting
 * @param string $xtype The xtype for the setting, for rendering purposes
 * @param string $area The area for the setting
 * @param string $namespace The namespace for the setting
 * @param string $name The lexicon name for the setting
 * @param string $description The lexicon description for the setting
 * @package MODX\Revolution\Processors\System\Settings
 */
class Create extends CreateProcessor
{
    public $classKey = modSystemSetting::class;
    public $languageTopics = ['setting', 'namespace'];
    public $permission = 'settings';
    public $objectType = 'setting';
    public $primaryKeyField = 'key';

    /**
     * Verify the Namespace passed is a valid Namespace
     * @return string|null
     */
    public function verifyNamespace()
    {
        $namespace = $this->getProperty('namespace', '');
        if (empty($namespace)) {
            $this->addFieldError('namespace', $this->modx->lexicon('namespace_err_ns'));
        }
        $namespace = $this->modx->getObject(modNamespace::class, $namespace);
        if (!$namespace) {
            $this->addFieldError('namespace', $this->modx->lexicon('namespace_err_nf'));
        }
    }

    /**
     * Verify setting key
     */
    public function verifySettingKey()
    {
        /* prevent empty or already existing settings */
        $key = trim($this->getProperty('key', ''));
        if (empty($key)) {
            $this->addFieldError('key', $this->modx->lexicon($this->objectType . '_err_ns'));
        }
        /* prevent keys starting with numbers */
        if (is_numeric($key[0])) {
            $this->addFieldError('key', $this->modx->lexicon($this->objectType . '_err_startint'));
        }
        $this->setProperty('key', $key);
        $this->object->set('key', $key);
    }

    /**
     * If this is a Boolean setting, ensure the value of the setting is 1/0
     * @return mixed
     */
    public function checkForBooleanValue()
    {
        $xtype = $this->getProperty('xtype', 'textfield');
        $value = $this->getProperty('value');
        if ($xtype === 'combo-boolean' && !is_numeric($value)) {
            $value = in_array($value, ['yes', 'Yes', $this->modx->lexicon('yes'), 'true', 'True']) ? 1 : 0;
            $this->object->set('value', $value);
        }
        return $value;
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->checkForBooleanValue();
        $this->verifyNamespace();
        $this->verifySettingKey();

        if ($this->alreadyExists()) {
            $this->addFieldError('key', $this->modx->lexicon($this->objectType . '_err_ae'));
        }

        return !$this->hasErrors();

    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function afterSave()
    {
        $this->setLexiconEntries($this->object->toArray());
        $this->modx->reloadConfig();
        
        return true;
    }

    /**
     * Check to see if a Setting already exists with this key
     * @return boolean
     */
    public function alreadyExists()
    {
        return $this->doesAlreadyExist(['key' => $this->getProperty('key')]);
    }

    /**
     * @param array $fields
     * @return void
     */
    public function setLexiconEntries(array $fields)
    {
        /* set lexicon name/description */
        if (!empty($fields['name'])) {
            /** @var modLexiconEntry $entry */
            $entry = $this->modx->getObject(modLexiconEntry::class, [
                'namespace' => $this->object->get('namespace'),
                'topic' => 'default',
                'name' => 'setting_' . $this->object->get('key'),
            ]);
            if ($entry === null) {
                $entry = $this->modx->newObject(modLexiconEntry::class);
                $entry->set('namespace', $this->object->get('namespace'));
                $entry->set('name', 'setting_' . $this->object->get('key'));
                $entry->set('value', $fields['name']);
                $entry->set('topic', 'default');
                $entry->set('language', $this->modx->cultureKey);
                $entry->save();

                $entry->clearCache();
            }
        }
        if (!empty($fields['description'])) {
            /** @var modLexiconEntry $description */
            $description = $this->modx->getObject(modLexiconEntry::class, [
                'namespace' => $this->object->get('namespace'),
                'topic' => 'default',
                'name' => 'setting_' . $this->object->get('key') . '_desc',
            ]);
            if ($description === null) {
                $description = $this->modx->newObject(modLexiconEntry::class);
                $description->set('namespace', $this->object->get('namespace'));
                $description->set('name', 'setting_' . $this->object->get('key') . '_desc');
                $description->set('value', $fields['description']);
                $description->set('topic', 'default');
                $description->set('language', $this->modx->cultureKey);
                $description->save();

                $description->clearCache();
            }
        }
    }
}
