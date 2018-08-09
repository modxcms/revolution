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
 * Grabs a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 * @var modX $modx
 */

class modPropertySetGetProcessor extends modObjectGetProcessor {
    public $objectType = 'propertyset';
    public $classKey = 'modPropertySet';
    public $permission = 'view_propertyset';
    public $languageTopics = array('propertyset');

    /** @var string The ID of an element in modElementPropertySet key */
    public $elementKey = 'elementId';
    /** @var string The class of an element in modElementPropertySet key */
    public $element_class = 'elementType';

    public $default = array();
    public $elementId;
    public $isDefault = false;
    public $props = array();

    /**
     * Get default properties of an element
     * @return array|mixed|null
     */
    public function getDefaultSet() {
        $default = array();
        $this->elementId = $this->getProperty($this->elementKey);
        $elementType = $this->getProperty($this->element_class);
        if (!empty($this->elementId) && !empty($elementType)) {
            /** @var modElement $element */
            $element = $this->modx->getObject($elementType, $this->elementId);
            if ($element) {
                $default = $element->get('properties');
                if (!is_array($default)) $default = array();
            }
        }
        return $default;
    }

    /**
     * Prepare default set
     * @param $default
     */
    public function prepareDefaultSet($default) {
        $this->isDefault = true;
        $this->object = $this->modx->newObject($this->classKey);
        $this->object->set($this->primaryKeyField, 0);
        $this->object->set('name', $this->modx->lexicon('default'));
        $this->object->set('properties', $default);
    }

    /**
     * Initialize property set
     * @return bool|null|string
     */
    public function initialize() {
        $this->default = $this->getDefaultSet();
        $id = $this->getProperty($this->primaryKeyField);
        if ($id == 0) {
            if (empty($this->default)) {
                return $this->modx->lexicon($this->objectType.'_err_nfs',array('id' => $id));
            }
            $this->prepareDefaultSet($this->default);
            return true;
        } else {
            return parent::initialize();
        }
    }

    /**
     * Get value of overridden status of property
     * @param $property
     * @param $data
     * @return bool|int
     */
    public function getOverridden($property, $data) {
        $overridden = false;
        /* if overridden, set flag */
        if (isset($data[$property['name']]) && !$this->isDefault) {
            $overridden = 1;
        }
        /* if completely new value, unique to set */
        if (!isset($data[$property['name']]) && !empty($this->elementId)) {
            $overridden = 2;
        }

        return $overridden;
    }

    /**
     * Load lexicon string for text option
     * @param $property
     */
    public function loadLexicons(&$property) {
        if (is_array($property['options'])) {
            foreach($property['options'] as &$option) {
                if (empty($option['text']) && !empty($option['name'])) $option['text'] = $option['name'];
                $option['text'] = !empty($property['lexicon']) ? $this->modx->lexicon($option['text']) : $option['text'];
            }
        }
    }

    /**
     * Form data field from properties
     * @param $properties
     * @param array $data
     * @param bool $isDefault
     */
    public function setData(array $properties, array &$data, $isDefault = false) {
        foreach ($properties as $property) {
            $this->loadLexicons($property);
            $data[$property['name']] = array(
                $property['name'],
                $property['desc'],
                !empty($property['type']) ? $property['type'] : 'textfield',
                !empty($property['options']) ? $property['options'] : array(),
                $property['value'],
                !empty($property['lexicon']) ? $property['lexicon'] : '',
                $isDefault ? false : $this->getOverridden($property, $data),
                $property['desc_trans'],
                !empty($property['area']) ? $property['area'] : '',
                !empty($property['area_trans']) ? $property['area_trans'] : ($isDefault ? '' : $property['area']),
            );
        }
    }

    /**
     * Used for adding custom data in derivative types
     * @return void
     */
    public function beforeOutput() {
        /* get set properties */
        $properties = $this->object->get('properties');
        if (!is_array($properties)) $properties = array();
        $data = array();
        if (!empty($this->default)) {
            $this->setData($this->default, $data, true);
        }
        $this->setData($properties, $data, false);
        $this->props = array_values($data);

        $this->beforeCleanup();
    }

    /**
     * Set data to object
     * @return void
     */
    public function beforeCleanup() {
        $this->object->set('data','(' . $this->modx->toJSON($this->props) . ')');
    }

}

return 'modPropertySetGetProcessor';
