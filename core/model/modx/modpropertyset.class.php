<?php
/**
 * @package modx
 */
/**
 * Represents a reusable set of properties for elements.
 *
 * Each named property set can be associated with one or more element instances
 * and can be called via a tag syntax or programatically.
 *
 * @property string $name A name for the set
 * @property int $category Optional. The category this Set belongs to
 * @property string $description A description of the set
 * @property array $properties An array of properties contained in this Property Set
 * @package modx
 * @extends xPDOSimpleObject
 */
class modPropertySet extends xPDOSimpleObject {
    /**
     * The property value array for the element.
     * @var array
     * @access protected
     */
    protected $_properties= null;

    /**
     * Get all the modElement instances this property set is available to.
     *
     * @access public
     * @return array An array of modElement instances.
     */
    public function getElements() {
        $elements = array();
        $links = $this->getMany('Elements');
        foreach ($links as $link) {
            $element = $link->getOne('Element');
            if ($element) $elements[] = $element;
        }
        return $elements;
    }
    
    /**
     * Overrides xPDOObject::get to handle when retrieving the properties field
     * for an Element.
     *
     * {@inheritdoc}
     */
    public function get($k, $format= null, $formatTemplate= null) {
        $value = parent :: get($k, $format, $formatTemplate);

        /* automatically translate lexicon descriptions */
        if ($k == 'properties' && !empty($value) && is_array($value)
               && is_object($this->xpdo) && $this->xpdo instanceof modX && $this->xpdo->lexicon) {
            foreach ($value as &$property) {
                if (!empty($property['lexicon'])) {
                    if (strpos($property['lexicon'],':') !== false) {
                        $this->xpdo->lexicon->load('en:'.$property['lexicon']);
                    } else {
                        $this->xpdo->lexicon->load('en:core:'.$property['lexicon']);
                    }
                    $this->xpdo->lexicon->load($property['lexicon']);
                }
                $property['desc_trans'] = $this->xpdo->lexicon($property['desc']);
                $property['area'] = !empty($property['area']) ? $property['area'] : '';
                
                if (!empty($property['options'])) {
                    foreach ($property['options'] as &$option) {
                        if (empty($option['text']) && !empty($option['name'])) {
                            $option['text'] = $option['name'];
                            unset($option['name']);
                        }
                        if (empty($option['value']) && !empty($option[0])) {
                            $option['value'] = $option[0];
                            unset($option[0]);
                        }
                        $option['name'] = $this->xpdo->lexicon($option['text']);
                    }
                }
            }
        }
        return $value;
    }

    /**
     * Get the properties for this element instance for processing.
     *
     * @access public
     * @param array|string $properties An array or string of properties to
     * apply.
     * @return array A simple array of properties ready to use for processing.
     */
    public function getProperties($properties = null) {
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
    public function setProperties($properties, $merge = false) {
        $set = false;
        $propertiesArray = array();
        if (is_string($properties)) {
            $properties = $this->xpdo->parser->parsePropertyString($properties);
        }
        if (is_array($properties)) {
            foreach ($properties as $propKey => $property) {
                if (is_array($property) && isset($property[5])) {
                    $key = $property[0];
                    $propertyArray = array(
                        'name' => $property[0],
                        'desc' => $property[1],
                        'type' => $property[2],
                        'options' => $property[3],
                        'value' => $property[4],
                        'lexicon' => !empty($property[5]) ? $property[5] : null,
                        'area' => !empty($property[6]) ? $property[6] : '',
                    );
                } elseif (is_array($property) && isset($property['value'])) {
                    $key = $property['name'];
                    $propertyArray = array(
                        'name' => $property['name'],
                        'desc' => isset($property['description']) ? $property['description'] : (isset($property['desc']) ? $property['desc'] : ''),
                        'type' => isset($property['xtype']) ? $property['xtype'] : (isset($property['type']) ? $property['type'] : 'textfield'),
                        'options' => isset($property['options']) ? $property['options'] : array(),
                        'value' => $property['value'],
                        'lexicon' => !empty($property['lexicon']) ? $property['lexicon'] : null,
                        'area' => !empty($property['area']) ? $property['area'] : '',
                    );
                } else {
                    $key = $propKey;
                    $propertyArray = array(
                        'name' => $propKey,
                        'desc' => '',
                        'type' => 'textfield',
                        'options' => array(),
                        'value' => $property,
                        'lexicon' => null,
                        'area' => '',
                    );
                }
                
                if ($propertyArray['type'] == 'combo-boolean' && is_numeric($propertyArray['value'])) {
                    $propertyArray['value'] = (boolean)$propertyArray['value'];
                }
                
                /* handle translations of properties (temp fix until modLocalizableObject in 2.1 and beyond) */
                /*if (!empty($propertyArray['lexicon'])) {
                    $this->xpdo->lexicon->load($propertyArray['lexicon']);
                    $propertyArray['desc'] = $this->xpdo->lexicon($propertyArray['desc']);

                    if (is_array($propertyArray['options'])) {
                        foreach ($propertyArray['options'] as $optionKey => &$option) {
                            if (!empty($option['text'])) {
                                $option['text'] = $this->xpdo->lexicon($option['text']);
                            }
                        }
                    }
                }*/
                $propertiesArray[$key] = $propertyArray;
            }
            if ($merge && !empty($propertiesArray)) {
                $existing = $this->get('properties');
                if (is_array($existing) && !empty($existing)) {
                    $propertiesArray = array_merge($existing, $propertiesArray);
                }
            }
            $set = $this->set('properties', $propertiesArray);
        }
        return $set;
    }


    /**
     * Overrides xPDOObject::save to fire modX-specific events.
     *
     * {@inheritDoc}
     */
    public function save($cacheFlag = null) {
        $isNew = $this->isNew();
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnPropertySetBeforeSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'propertySet' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        }

        $saved = parent :: save($cacheFlag);

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnPropertySetSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'propertySet' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        }
        return $saved;
    }

    /**
     * Overrides xPDOObject::remove to fire modX-specific events.
     *
     * {@inheritDoc}
     */
    public function remove(array $ancestors = array()) {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnPropertySetBeforeRemove',array(
                'propertySet' => &$this,
                'ancestors' => $ancestors,
            ));
        }

        $removed = parent :: remove($ancestors);

        if ($removed && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnPropertySetRemove',array(
                'propertySet' => &$this,
                'ancestors' => $ancestors,
            ));
        }
        return $removed;
    }
}
