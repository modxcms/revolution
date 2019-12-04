<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\System\Settings;

use MODX\Revolution\modNamespace;
use MODX\Revolution\Processors\Model\UpdateProcessor;
use MODX\Revolution\modResource;
use MODX\Revolution\modSystemSetting;

/**
 * Update a system setting
 * @property string $key         The key of the setting
 * @property string $value       The value of the setting
 * @property string $xtype       The xtype for the setting, for rendering purposes
 * @property string $area        The area for the setting
 * @property string $namespace   The namespace for the setting
 * @property string $name        The lexicon name for the setting
 * @property string $description The lexicon description for the setting
 * @package MODX\Revolution\Processors\Context\Setting
 */
class Update extends UpdateProcessor
{
    public $classKey = modSystemSetting::class;
    public $languageTopics = ['setting', 'namespace'];
    public $permission = 'settings';
    public $objectType = 'setting';
    public $primaryKeyField = 'key';

    /** @var modSystemSetting $object */
    public $object;

    /** @var boolean $refreshURIs */
    public $refreshURIs = false;

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->verifyNamespace();
        $this->checkForBooleanValue();
        $this->refreshURIs = $this->checkForRefreshURIs();

        return parent::beforeSave();
    }

    /**
     * @return bool
     */
    public function afterSave()
    {
        $this->updateTranslations($this->getProperties());
        $this->refreshURIs();
        $this->clearCache();

        return parent::afterSave();
    }

    /**
     * Verify the Namespace passed is a valid Namespace
     *
     * @return string|null
     */
    public function verifyNamespace()
    {
        $namespaceKey = $this->getProperty('namespace');
        if (empty($namespaceKey)) {
            $this->addFieldError('namespace', $this->modx->lexicon('namespace_err_ns'));
        } else {
            $namespace = $this->modx->getObject(modNamespace::class, $namespaceKey);
            if ($namespace === null) {
                $this->addFieldError('namespace', $this->modx->lexicon('namespace_err_nf'));
            }
        }

        return $namespaceKey;
    }

    /**
     * If this is a Boolean setting, ensure the value of the setting is 1/0
     *
     * @return mixed
     */
    public function checkForBooleanValue()
    {
        $xtype = $this->getProperty('xtype');
        $value = $this->getProperty('value');
        if ($xtype === 'combo-boolean' && !is_numeric($value)) {
            $value = in_array($value, ['yes', 'Yes', $this->modx->lexicon('yes'), 'true', 'True', true], true) ? 1 : 0;
            $this->object->set('value', $value);
        }

        return $value;
    }

    /**
     * Check to see if the URIs need to be refreshed
     *
     * @return boolean
     */
    public function checkForRefreshURIs()
    {
        $refresh = false;
        if ($this->object->get('key') === 'friendly_urls' && $this->object->isDirty('value') && $this->object->get('value') === '1') {
            $refresh = true;
        } else if ($this->object->get('key') === 'use_alias_path' && $this->object->isDirty('value')) {
            $refresh = true;
        } else if ($this->object->get('key') === 'container_suffix' && $this->object->isDirty('value')) {
            $refresh = true;
        }

        return $refresh;
    }

    /**
     * Update lexicon name/description
     *
     * @param array $fields
     *
     * @return void
     */
    public function updateTranslations(array $fields)
    {
        if (isset($fields['name'])) {
            $this->object->updateTranslation('setting_' . $this->object->get('key'), $fields['name'], [
                'namespace' => $this->object->get('namespace'),
            ]);
        }

        if (isset($fields['description'])) {
            $this->object->updateTranslation('setting_' . $this->object->get('key') . '_desc', $fields['description'], [
                'namespace' => $this->object->get('namespace'),
            ]);
        }
    }

    /**
     * If friendly_urls is set on or use_alias_path changes, refreshURIs
     *
     * @return boolean
     */
    public function refreshURIs()
    {
        if ($this->refreshURIs) {
            $this->modx->setOption($this->object->get('key'), $this->object->get('value'));
            $this->modx->call(modResource::class, 'refreshURIs', [&$this->modx]);
        }

        return $this->refreshURIs;
    }

    /**
     * Clear the settings cache and reload the config
     *
     * @return void
     */
    public function clearCache()
    {
        $this->modx->reloadConfig();
    }
}
