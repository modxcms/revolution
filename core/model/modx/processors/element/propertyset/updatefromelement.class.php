<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

include_once dirname(__FILE__).'/update.class.php';

/**
 * Saves a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */

class modPropertySetUpdateFromElementProcessor extends modPropertySetUpdateProcessor {
    public $languageTopics = array('propertyset', 'category', 'element');

    /** @var modPropertySet|null */
    public $object;

    /** @var modElement|null  */
    public $element = null;

    /**
     * Get element, if necessary
     * @return null|modElement
     */
    public function getElement() {
        $elementId = (int)$this->getProperty('elementId', 0);
        $elementClass = $this->getProperty('elementType', '');
        if ($elementId && !empty($elementClass)) {
            return $this->modx->getObject($elementClass, $elementId);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     * @return bool|null|string
     */
    public function initialize() {
        $this->element = $this->getElement();

        $primaryKey = $this->getProperty($this->primaryKeyField, false);
        if ($primaryKey == 'Default') {
            $primaryKey = 0;
            $this->setProperty($this->primaryKeyField, 0);
        }

        if (!$primaryKey) {
            if (!$this->element) return $this->modx->lexicon('element_err_ns');
            return true;
        }

        return parent::initialize();
    }

    /**
     * Return data as array
     * @return mixed
     */
    public function getData() {
        return $this->modx->fromJSON($this->getProperty('data'));
    }

    /**
     * Convert JSON data to array and unset default properties
     * @return bool
     */
    public function beforeSave() {
        $data = $this->getData();

        if ($this->element) {
            $default = $this->element->getProperties();
            foreach ($data as $k => $prop) {
                if (array_key_exists($prop['name'],$default)) {
                    if ($prop['value'] == $default[$prop['name']]) {
                        unset($data[$k]);
                    }
                }
            }
        }

        $this->setProperty('data', $data);

        return parent::beforeSave();
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process() {
        if (!$this->object) {
            $this->element->setProperties($this->getData());
            $this->element->save();
        } else {
            /* Run the beforeSave method and allow stoppage */
            $canSave = $this->beforeSave();
            if ($canSave !== true) {
                return $this->failure($canSave);
            }

            $this->object->setProperties($this->getProperty('data'));

            if ($this->saveObject() == false) {
                return $this->failure($this->modx->lexicon($this->objectType.'_err_save'));
            }
        }

        $this->logManagerAction();
        return $this->success();
    }

    /**
     * Log the property set update from element manager action
     * @return void
     */
    public function logManagerAction() {
        $key = $this->object ? $this->object->get($this->primaryKeyField) :
            $this->getProperty('elementType') . ' ' . $this->getProperty('elementId') .  ' Default';
        $this->modx->logManagerAction($this->objectType.'_update_from_element', $this->classKey, $key);
    }
}

return 'modPropertySetUpdateFromElementProcessor';
