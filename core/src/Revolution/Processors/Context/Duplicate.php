<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Context;


use MODX\Revolution\modAccessContext;
use MODX\Revolution\modContext;
use MODX\Revolution\modContextSetting;
use MODX\Revolution\Processors\Model\DuplicateProcessor;
use MODX\Revolution\modResource;
use MODX\Revolution\Sources\modMediaSourceElement;

/**
 * Duplicates a context.
 *
 * @property string $key    The key of the context
 * @property string $newkey The new key of the duplicated context
 *
 * @package MODX\Revolution\Processors\Context
 */
class Duplicate extends DuplicateProcessor
{
    public $classKey = modContext::class;
    public $languageTopics = ['context'];
    public $permission = 'new_context';
    public $objectType = 'context';
    public $primaryKeyField = 'key';
    public $nameField = 'key';
    public $newNameField = 'newkey';

    public function afterSave()
    {
        $this->duplicateSettings();
        $this->duplicateAccessControlLists();
        $this->reloadPermissions();
        $this->duplicateMediaSourceElements();
        if (($this->getProperty('preserve_resources') == 'on')) {
            $this->duplicateResources();
        }

        return parent::afterSave();
    }

    /**
     * Validate the passed properties for the new context
     *
     * @return boolean
     */
    public function beforeSave()
    {
        $newKey = $this->getProperty($this->newNameField);
        /* make sure the new key is a valid PHP identifier with no underscore characters */
        if (empty($newKey) || !preg_match('/^[a-zA-Z\x7f-\xff][a-zA-Z0-9\x2d-\x2f\x7f-\xff]*$/', $newKey)) {
            $this->addFieldError($this->newNameField, $this->modx->lexicon('context_err_ns_key'));
        }

        return parent::beforeSave();
    }

    /**
     * Get the new name for the duplicate
     *
     * @return string
     */
    public function getNewName()
    {
        $name = $this->getProperty($this->newNameField);
        $newName = !empty($name) ? $name : $this->modx->lexicon('duplicate_of',
            ['name' => $this->object->get($this->nameField)]);

        return $newName;
    }

    /**
     * Duplicate the settings of the old Context to the new one
     *
     * @return array
     */
    public function duplicateSettings()
    {
        $duplicatedSettings = [];
        $settings = $this->modx->getCollection(modContextSetting::class, [
            'context_key' => $this->object->get('key'),
        ]);
        /** @var modContextSetting $setting */
        foreach ($settings as $setting) {
            /** @var $newSetting modContextSetting */
            $newSetting = $this->modx->newObject(modContextSetting::class);
            $newSetting->fromArray($setting->toArray(), '', true, true);
            $newSetting->set('context_key', $this->newObject->get('key'));
            $newSetting->save();
            $duplicatedSettings[] = $newSetting;
        }

        return $duplicatedSettings;
    }

    /**
     * Duplicate the ACLs of the old Context into the new one
     *
     * @return array
     */
    public function duplicateAccessControlLists()
    {
        $duplicatedACLs = [];
        $permissions = $this->modx->getCollection(modAccessContext::class, [
            'target' => $this->object->get('key'),
        ]);
        /** @var modAccessContext $acl */
        foreach ($permissions as $acl) {
            /** @var modAccessContext $newAcl */
            $newAcl = $this->modx->newObject(modAccessContext::class);
            $newAcl->fromArray($acl->toArray(), '', false, true);
            $newAcl->set('target', $this->newObject->get('key'));
            $newAcl->save();
            $duplicatedACLs[] = $newAcl;
        }

        return $duplicatedACLs;
    }

    /**
     * Flush permissions for the mgr user to properly handle the new context
     *
     * @return void
     */
    public function reloadPermissions()
    {
        if ($this->modx->getUser()) {
            $this->modx->user->getAttributes([], '', true);
        }
    }

    /**
     * Duplicate the MediaSourceElements of the old Context into the new one
     *
     * @return array
     */
    public function duplicateMediaSourceElements()
    {
        $duplicatedElements = [];
        $mediaSourcesElements = $this->modx->getCollection(modMediaSourceElement::class, [
            'context_key' => $this->object->get('key'),
        ]);

        /** @var modMediaSourceElement $mediaSourcesElement */
        foreach ($mediaSourcesElements as $mediaSourcesElement) {
            /** @var modMediaSourceElement $newMediaSourcesElement */
            $newMediaSourcesElement = $this->modx->newObject(modMediaSourceElement::class);
            $newMediaSourcesElement->set('source', $mediaSourcesElement->get('source'));
            $newMediaSourcesElement->set('object_class', $mediaSourcesElement->get('object_class'));
            $newMediaSourcesElement->set('object', $mediaSourcesElement->get('object'));
            $newMediaSourcesElement->set('context_key', $this->newObject->get('key'));
            $newMediaSourcesElement->save();
            $duplicatedElements[] = $mediaSourcesElement;
        }

        return $duplicatedElements;
    }

    /**
     * Duplicate the Resources of the old Context into the new one
     *
     * @return void
     */
    public function duplicateResources()
    {
        $criteria = [
            'context_key' => $this->object->get('key'),
            'parent' => 0,
        ];
        $count = $this->modx->getCount(modResource::class, $criteria);

        if ($count > 0) {
            $resources = $this->modx->getIterator(modResource::class, $criteria);

            /** @var modResource $resource */
            foreach ($resources as $resource) {
                $resource->duplicate([
                    'prefixDuplicate' => false,
                    'duplicateChildren' => true,
                    'overrides' => [
                        'context_key' => $this->newObject->get('key'),
                    ],
                    'preserve_alias' => ($this->getProperty('preserve_alias') == 'on') ? true : false,
                    'preserve_menuindex' => ($this->getProperty('preserve_menuindex') == 'on') ? true : false,
                ]);
            }
        }
    }
}
