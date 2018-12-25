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
 * Adds an element to a Property Set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */

class modPropertySetAddElementProcessor extends modObjectProcessor {
    public $classKey = 'modElementPropertySet';
    public $objectType = 'propertyset';
    public $permission = 'save_propertyset';
    public $languageTopics = array('propertyset', 'element');

    /** @var string The ID of an element in modElementPropertySet key */
    public $elementKey = 'element';
    /** @var string The class of an element in modElementPropertySet key */
    public $element_class = 'element_class';
    /** @var string The property set in modElementPropertySet key */
    public $propertySetKey = 'property_set';

    /**
     * Grab element to check its existence
     * @return bool|null|string
     */
    public function getElement() {
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
     * @param $propertySetId
     * @return bool|null|string
     */
    public function getPropertySet(&$propertySetId) {
        $propertySetId = (int) $this->getProperty('propertyset');
        if (!$propertySetId) {
            return $this->modx->lexicon($this->objectType.'_err_ns');
        }

        $set = $this->modx->getObject('modPropertySet', $propertySetId);
        if (!$set) {
            return $this->modx->lexicon($this->objectType.'_err_nfs', array('id' => $propertySetId));
        }

        return true;
    }

    /**
     * {@inheritdoc}
     * @return bool|null|string
     */
    public function initialize() {
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
     * @return void
     */
    public function logManagerAction() {
        $item = $this->object->get($this->element_class) . ' ' . $this->object->get($this->elementKey) .
            ', modPropertySet ' . $this->object->get($this->propertySetKey);
        $this->modx->logManagerAction($this->objectType.'_element_add', $this->classKey, $item);
    }

    public function process() {
        $this->object->fromArray($this->getProperties(),'',true);
        if ($this->object->save() === false) {
            return $this->failure($this->modx->lexicon($this->objectType.'_err_element_add'));
        }
        $this->logManagerAction();
        return $this->cleanup();
    }

    /**
     * Return the success message
     * @return array
     */
    public function cleanup() {
        return $this->success('', $this->object);
    }

}

return 'modPropertySetAddElementProcessor';
