<?php

namespace MODX\Processors\Element\PropertySet;

/**
 * Gets properties for a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
class GetProperties extends Get
{
    /** @var string The ID of an element in modElementPropertySet key */
    public $elementKey = 'element';
    /** @var string The class of an element in modElementPropertySet key */
    public $element_class = 'element_class';


    /**
     * No need to do something here
     */
    public function beforeCleanup()
    {
    }


    /**
     * Output properties instead of property set
     *
     * @return array|string
     */
    public function cleanup()
    {
        return $this->success('', $this->props);
    }
}
