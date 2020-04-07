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


use MODX\Revolution\Processors\Model\GetProcessor;

/**
 * Abstract class for Get Element processors. To be extended for each derivative element type.
 *
 * @abstract
 *
 * @package MODX\Revolution\Processors\Element
 */
abstract class Get extends GetProcessor
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
        if (!is_array($properties)) {
            $properties = [];
        }

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

        $this->object->set('data', '(' . $this->modx->toJSON($data) . ')');

        return $data;
    }
}
