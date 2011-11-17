<?php
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
                $property['type'],
                $property['options'],
                $property['value'],
                $property['lexicon'],
                false, /* overridden set to false */
                $property['desc'],
            );
        }

        $this->object->set('data','(' . $this->modx->toJSON($data) . ')');
        return $data;
    }
}