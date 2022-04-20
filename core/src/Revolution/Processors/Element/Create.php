<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element;

use MODX\Revolution\modCategory;
use MODX\Revolution\modElement;
use MODX\Revolution\Processors\Model\CreateProcessor;
use MODX\Revolution\modTemplate;
use MODX\Revolution\Validation\modValidator;

/**
 * Abstract class for Create Element processors. To be extended for each derivative element type.
 *
 * @abstract
 *
 * @package MODX\Revolution\Processors\Element
 */
abstract class Create extends CreateProcessor
{
    /** @var modElement $object */
    public $object;

    /**
     * Cleanup the process and send back the response
     *
     * @return array
     */
    public function cleanup()
    {
        $this->clearCache();
        $fields = ['id', 'description', 'locked', 'category'];
        array_push($fields, ($this->classKey == modTemplate::class ? 'templatename' : 'name'));

        return $this->success('', $this->object->get($fields));
    }

    /**
     * Validate the form
     *
     * @return boolean
     */
    public function beforeSave()
    {
        $nameField = $this->classKey === modTemplate::class ? 'templatename' : 'name';
        $name = $this->getProperty($nameField, '');

        /* verify element with that name does not already exist */
        if ($this->alreadyExists($name)) {
            $this->addFieldError($nameField, $this->modx->lexicon($this->objectType . '_err_ae', [
                'name' => $name,
            ]));
        }

        $category = $this->getProperty('category', 0);
        if (!empty($category)) {
            /** @var modCategory $category */
            $category = $this->modx->getObject(modCategory::class, ['id' => $category]);
            if ($category === null) {
                $this->addFieldError('category', $this->modx->lexicon('category_err_nf'));
            }
            if ($category !== null && !$category->checkPolicy('add_children')) {
                $this->addFieldError('category', $this->modx->lexicon('access_denied'));
            }
        }

        $locked = (bool)$this->getProperty('locked', false);
        $this->object->set('locked', $locked);

        $this->setElementProperties();
        $this->validateElement();

        if ($this->object->staticContentChanged()) {
            if ($this->object->get('content') !== '' && !$this->object->isStaticSourceMutable()) {
                $this->addFieldError('static_file', $this->modx->lexicon('element_static_source_immutable'));
            } elseif (!$this->object->isStaticSourceValidPath()) {
                $this->addFieldError('static_file', $this->modx->lexicon('element_static_source_protected_invalid'));
            }
        }

        return !$this->hasErrors();
    }

    /**
     * Check to see if a Chunk already exists with specified name
     *
     * @param string $name
     *
     * @return bool
     */
    public function alreadyExists($name)
    {
        if ($this->classKey == modTemplate::class) {
            $c = ['templatename' => $name];
        } else {
            $c = ['name' => $name];
        }

        return $this->modx->getCount($this->classKey, $c) > 0;
    }

    /**
     * Set the properties on the Element
     *
     * @return mixed
     */
    public function setElementProperties()
    {
        $properties = null;
        $propertyData = $this->getProperty('propdata', null);
        if ($propertyData != null && is_string($propertyData)) {
            $propertyData = $this->modx->fromJSON($propertyData);
        }
        if (is_array($propertyData)) {
            $this->object->setProperties($propertyData);
        }

        return $propertyData;
    }

    /**
     * Run object-level validation on the element
     *
     * @return void
     */
    public function validateElement()
    {
        if (!$this->object->validate()) {
            /** @var modValidator $validator */
            $validator = $this->object->getValidator();
            if ($validator->hasMessages()) {
                foreach ($validator->getMessages() as $message) {
                    $this->addFieldError($message['field'], $this->modx->lexicon($message['message']));
                }
            }
        }
    }

    /**
     * Clear the cache post-save
     *
     * @return void
     */
    public function clearCache()
    {
        if ($this->getProperty('clearCache')) {
            $this->modx->cacheManager->refresh();
        }
    }
}
