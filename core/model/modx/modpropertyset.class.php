<?php
/**
 * Represents a reusable set of properties for elements.
 *
 * Each named property set can be associated with one or more element instances
 * and can be called via a tag syntax or programatically.
 *
 * @package modx
 * @extends xPDOSimpleObject
 */
class modPropertySet extends xPDOSimpleObject {
    /**
     * The property value array for the element.
     * @var array
     * @access private
     */
    var $_properties= null;

    /**#@+
     * Creates an instance of a modPropertySet.
     *
     * {@inheritdoc}
     */
    function modPropertySet(& $xpdo) {
        $this->__construct($xpdo);
    }
    /** @ignore */
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
    /**#@-*/

    /**
     * Get all the modElement instances this property set is available to.
     *
     * @access public
     * @return array An array of modElement instances.
     */
    function getElements() {
        $elements = array();
        $links = $this->getMany('Elements');
        foreach ($links as $link) {
            $element = $link->getOne('Element');
            if ($element) $elements[] = $element;
        }
        return $elements;
    }

    /**
     * Get the properties for this element instance for processing.
     *
     * @access public
     * @param array|string $properties An array or string of properties to
     * apply.
     * @return array A simple array of properties ready to use for processing.
     */
    function getProperties($properties = null) {
        $this->xpdo->getParser();
        $this->_properties= $this->xpdo->parser->parseProperties($this->get('properties'));
        if (!empty($properties)) {
            $this->_properties= array_merge($this->_properties, $this->xpdo->parser->parseProperties($properties));
        }
        return $this->_properties;
    }

    /**
     * Set properties for this modPropertySet instance.
     *
     * @access public
     * @param array|string $properties A property array or property string.
     * @param boolean $merge Indicates if properties should be merged with
     * existing ones.
     * @return boolean true if the properties are set.
     */
    function setProperties($properties, $merge = false) {
        $set = false;
        $propertyArray = array();
        if (is_string($properties)) {
            $properties = $this->xpdo->parser->parsePropertyString($properties);
        }
        if (is_array($properties)) {
            foreach ($properties as $propKey => $property) {
                if (is_array($property) && isset($property[5])) {
                    $propertyArray[$property[0]] = array(
                        'name' => $property[0],
                        'desc' => $property[1],
                        'type' => $property[2],
                        'options' => $property[3],
                        'value' => $property[4],
                    );
                } elseif (is_array($property) && isset($property['value'])) {
                    $propertyArray[$property['name']] = array(
                        'name' => $property['name'],
                        'desc' => isset($property['description']) ? $property['description'] : (isset($property['desc']) ? $property['desc'] : ''),
                        'type' => isset($property['xtype']) ? $property['xtype'] : (isset($property['type']) ? $property['type'] : 'textfield'),
                        'options' => isset($property['options']) ? $property['options'] : array(),
                        'value' => $property['value'],
                    );
                } else {
                    $propertyArray[$propKey] = array(
                        'name' => $propKey,
                        'desc' => '',
                        'type' => 'textfield',
                        'options' => array(),
                        'value' => $property
                    );
                }
            }
            if ($merge && !empty($propertyArray)) {
                $existing = $this->get('properties');
                if (is_array($existing) && !empty($existing)) {
                    $propertyArray = array_merge($existing, $propertyArray);
                }
            }
            $set = $this->set('properties', $propertyArray);
        }
        return $set;
    }
}
