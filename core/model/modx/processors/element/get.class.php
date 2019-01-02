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
 * Abstract class for Get Element processors. To be extended for each derivative element type.
 *
 * @abstract
 * @package modx
 * @subpackage processors.element
 */
abstract class modElementGetProcessor extends modObjectGetProcessor {
    /**
     * Used for adding custom data in derivative types
     * @return void
     */
    public function beforeOutput() {
        $this->getElementProperties();
    }

    /**
     * Get the properties of the element
     * @return array
     */
    public function getElementProperties() {
        $properties = $this->object->get('properties');
        if (!is_array($properties)) $properties = array();

        /* process data */
        $data = array();
        foreach ($properties as $property) {
            $data[] = array(
                $property['name'],
                $property['desc'],
                !empty($property['type']) ? $property['type'] : 'textfield',
                !empty($property['options']) ? $property['options'] : array(),
                $property['value'],
                !empty($property['lexicon']) ? $property['lexicon'] : '',
                false, /* overridden set to false */
                $property['desc_trans'],
                !empty($property['area']) ? $property['area'] : '',
            );
        }

        $this->object->set('data','(' . $this->modx->toJSON($data) . ')');
        return $data;
    }
}
