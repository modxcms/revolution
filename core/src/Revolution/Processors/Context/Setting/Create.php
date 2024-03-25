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
use MODX\Revolution\modLexiconEntry;
use MODX\Revolution\modNamespace;
use MODX\Revolution\Processors\Model\CreateProcessor;
use MODX\Revolution\modResource;

/**
 * Creates a context setting
 *
 * @property string $context_key/$fk The key of the context
 * @property string $key        The key of the setting
 * @property string $value      The value of the setting.
 * @property string $xtype      (optional) The rendering type for the setting. Defaults
 * to textfield.
 * @property string $namespace  (optional) The namespace of the setting. Defaults to
 * core.
 * @property string $area       (optional) The area of the setting. Defaults to a blank
 * area.
 *
 * @package MODX\Revolution\Processors\Context\Setting
 */
class Create extends CreateProcessor
{
    public $classKey = modContextSetting::class;
    public $languageTopics = ['setting', 'namespace'];
    public $permission = 'settings';
    public $objectType = 'setting';
    public $primaryKeyField = 'key';

    /** @var modContext */
    public $context;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $this->context = $this->modx->getContext($this->getProperty('fk'));
        if (empty($this->context)) {
            return $this->modx->lexicon('setting_err_nf');
        }

        return parent::initialize();
    }

    /**
     * Process the setting before saving
     *
     * @return boolean
     */
    public function beforeSave()
    {
        $this->object->set('context_key', $this->context->key);

        $key = trim($this->getProperty('key', ''));
        $this->object->set('key', $key);

        // Make sure the key's there and valid
        if (empty($key)) {
            $this->addFieldError('key', $this->modx->lexicon('setting_err_ns'));
        }
        if ($this->doesAlreadyExist([
            'key' => $this->getProperty('key'),
            'context_key' => $this->getProperty('context_key'),
        ])) {
            $this->addFieldError('key', $this->modx->lexicon('setting_err_ae'));

            return false;
        }

        // Prevent keys starting with a number or comma
        $nums = explode(',', '1,2,3,4,5,6,7,8,9,0');
        if (!empty($key) && in_array(substr($key, 0, 1), $nums)) {
            $this->addFieldError('key', $this->modx->lexicon('setting_err_startint'));
        }

        /* get namespace */
        $namespace = $this->getProperty('namespace');
        if (empty($namespace)) {
            $this->addFieldError('namespace', $this->modx->lexicon('namespace_err_ns'));
        }
        $namespace = $this->modx->getObject(modNamespace::class, $namespace);
        if (empty($namespace)) {
            $this->addFieldError('namespace', $this->modx->lexicon('namespace_err_nf'));
        }

        /* type parsing */
        $xtype = $this->getProperty('xtype', 'textfield');
        $value = $this->getProperty('value');
        if ($xtype == 'combo-boolean' && !is_numeric($value)) {
            if ($value == 'yes' || $value == 'Yes' || $value == $this->modx->lexicon('yes')) {
                $this->object->set('value', 1);
            } else {
                $this->object->set('value', 0);
            }
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

        $key = $this->getProperty('key');
        $value = $this->getProperty('value');

        $refreshURIs = false;
        if ($key === 'friendly_urls' && $value == '1') {
            $refreshURIs = true;
        }
        if ($key === 'use_alias_path') {
            $refreshURIs = true;
        }
        if ($key === 'container_suffix') {
            $refreshURIs = true;
        }
        if ($refreshURIs) {
            $this->context->config[$key] = $value;
            $this->modx->call(modResource::class, 'refreshURIs',
                [&$this->modx, 0, ['contexts' => $this->context->get('key')]]);
        }

        return true;
    }

    /**
     * @param array $fields
     *
     * @return void
     */
    public function setLexiconEntries(array $fields)
    {
        /* set lexicon name/description */
        if (!empty($fields['name'])) {
            /** @var modLexiconEntry $entry */
            $entry = $this->modx->getObject(modLexiconEntry::class, [
                'namespace' => $this->object->get('namespace'),
                'topic' => 'setting',
                'name' => 'setting_' . $this->object->get('key'),
            ]);
            if ($entry == null) {
                $entry = $this->modx->newObject(modLexiconEntry::class);
                $entry->set('namespace', $this->object->get('namespace'));
                $entry->set('name', 'setting_' . $this->object->get('key'));
                $entry->set('value', $fields['name']);
                $entry->set('topic', 'setting');
                $entry->set('language', $this->modx->cultureKey);
                $entry->save();

                $entry->clearCache();
            }
        }
        if (!empty($fields['description'])) {
            /** @var modLexiconEntry $description */
            $description = $this->modx->getObject(modLexiconEntry::class, [
                'namespace' => $this->object->get('namespace'),
                'topic' => 'setting',
                'name' => 'setting_' . $this->object->get('key') . '_desc',
            ]);
            if ($description == null) {
                $description = $this->modx->newObject(modLexiconEntry::class);
                $description->set('namespace', $this->object->get('namespace'));
                $description->set('name', 'setting_' . $this->object->get('key') . '_desc');
                $description->set('value', $fields['description']);
                $description->set('topic', 'setting');
                $description->set('language', $this->modx->cultureKey);
                $description->save();

                $description->clearCache();
            }
        }
    }
}
