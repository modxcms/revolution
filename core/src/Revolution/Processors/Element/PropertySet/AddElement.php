<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\PropertySet;


use MODX\Revolution\modElementPropertySet;
use MODX\Revolution\Processors\ModelProcessor;
use MODX\Revolution\modPropertySet;

/**
 * Adds an element to a Property Set
 *
 * @package MODX\Revolution\Processors\Element\PropertySet
 */
class AddElement extends ModelProcessor
{
    public $classKey = modElementPropertySet::class;
    public $objectType = 'propertyset';
    public $permission = 'save_propertyset';
    public $languageTopics = ['propertyset', 'element'];

    /** @var string The ID of an element in modElementPropertySet key */
    public $elementKey = 'element';
    /** @var string The class of an element in modElementPropertySet key */
    public $element_class = 'element_class';
    /** @var string The property set in modElementPropertySet key */
    public $propertySetKey = 'property_set';

    /**
     * Grab element to check its existence
     *
     * @return bool|null|string
     */
    public function getElement()
    {
        $elementClass = $this->getProperty($this->element_class);
        $elementId = $this->getProperty($this->elementKey);
        if (empty($elementClass) || empty($elementId)) {
            return $this->modx->lexicon('element_err_ns');
        }

        $element = $this->modx->getObject($elementClass, $elementId);
        if (!$element) {
            return $this->modx->lexicon('element_err_nf');
        }

        return true;
    }

    /**
     * Grab Property Set to check if it exists and get its ID
     *
     * @param $propertySetId
     *
     * @return bool|null|string
     */
    public function getPropertySet(&$propertySetId)
    {
        $propertySetId = (int)$this->getProperty('propertyset');
        if (!$propertySetId) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }

        $set = $this->modx->getObject(modPropertySet::class, $propertySetId);
        if (!$set) {
            return $this->modx->lexicon($this->objectType . '_err_nfs', ['id' => $propertySetId]);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     * @return bool|null|string
     */
    public function initialize()
    {
        $isSetExists = $this->getPropertySet($propertySetId);
        if ($isSetExists !== true) {
            return $isSetExists;
        }

        $isElementExists = $this->getElement();
        if ($isElementExists !== true) {
            return $isElementExists;
        }

        $this->setProperty($this->propertySetKey, $propertySetId);
        $this->unsetProperty('propertyset');
        $this->unsetProperty('action');

        $this->object = $this->modx->newObject($this->classKey);

        return parent::initialize();
    }

    /**
     * Log add element to property set
     *
     * @return void
     */
    public function logManagerAction()
    {
        $item = $this->object->get($this->element_class) . ' ' . $this->object->get($this->elementKey) .
            ', modPropertySet ' . $this->object->get($this->propertySetKey);
        $this->modx->logManagerAction($this->objectType . '_element_add', $this->classKey, $item);
    }

    public function process()
    {
        $this->object->fromArray($this->getProperties(), '', true);
        if ($this->object->save() === false) {
            return $this->failure($this->modx->lexicon($this->objectType . '_err_element_add'));
        }
        $this->logManagerAction();

        return $this->cleanup();
    }

    /**
     * Return the success message
     *
     * @return array
     */
    public function cleanup()
    {
        return $this->success('', $this->object);
    }

}
