<?php

namespace MODX\Processors\Element;

use MODX\Processors\modObjectGetProcessor;

/**
 * Abstract class for Get Element processors. To be extended for each derivative element type.
 *
 * @abstract
 * @package modx
 * @subpackage processors.element
 */
abstract class Get extends modObjectGetProcessor
{
    /**
     * Used for adding custom data in derivative types
     *
     * @return void
     */
    public function beforeOutput()
    {
        $this->getElementProperties();
    }


    /**
     * Get the properties of the element
     *
     * @return array
     */
    public function getElementProperties()
    {
        $properties = $this->object->get('properties');
        if (!is_array($properties)) $properties = [];

        /* process data */
        $data = [];
        foreach ($properties as $property) {
            $data[] = [
                $property['name'],
                $property['desc'],
                !empty($property['type']) ? $property['type'] : 'textfield',
                !empty($property['options']) ? $property['options'] : [],
                $property['value'],
                !empty($property['lexicon']) ? $property['lexicon'] : '',
                false, /* overridden set to false */
                $property['desc_trans'],
                !empty($property['area']) ? $property['area'] : '',
            ];
        }

        $this->object->set('data', '(' . json_encode($data) . ')');

        return $data;
    }
}