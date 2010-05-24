<?php
/*
 * MODx Revolution
 *
 * Copyright 2006-2010 by the MODx Team.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 */
/**
 * Represents an element of source content managed by MODx.
 *
 * These elements are defined by some type of source content that when processed
 * will provide output or some type of logical result based on mutable
 * properties.
 *
 * This class creates an instance of a modElement object. This should not be
 * called directly, but rather extended for derivative modElement classes.
 *
 * @package modx
 * @abstract Implement a derivative of this class to represent an element which
 * can be processed within the MODx framework.
 * @extends modAccessibleSimpleObject
 */
class modElement extends modAccessibleSimpleObject {
    /**
     * The property value array for the element.
     * @var array
     * @access private
     */
    public $_properties= null;
    /**
     * The string representation of the element properties.
     * @var string
     * @access private
     */
    public $_propertyString= '';
    /**
     * The source content of the element.
     * @var string
     * @access private
     */
    public $_content= '';
    /**
     * The output of the element.
     * @var string
     * @access private
     */
    public $_output= '';
    /**
     * The boolean result of the element.
     *
     * This is typically only applicable to elements that use PHP source content.
     * @var boolean
     * @access private
     */
    public $_result= true;
    /**
     * The tag signature of the element instance.
     * @var string
     * @access private
     */
    public $_tag= null;
    /**
     * The character token which helps identify the element class in tag string.
     * @var string
     * @access private
     */
    public $_token= '';
    /**
     * @var boolean If the element is cacheable or not.
     * @access private
     */
    public $_cacheable= true;
    /**
     * @var boolean Indicates if the element was processed already.
     * @access private
     */
    public $_processed= false;
    /**
     * @var array Optional filters that can be used during processing.
     * @access private
     */
    public $_filters= array ();

    /**
     * @var array A list of invalid characters in the name of an Element.
     * @access protected
     */
    protected $_invalidCharacters = array('!','@','#','$','%','^','&','*',
    '(',')','+','=','[',']','{','}','\'','"',';',':','\\','/','<','>','?'
    ,' ',',','`','~');

    /**
     * Overrides xPDOObject::set to strip invalid characters from element names.
     *
     * {@inheritDoc}
     */
    public function set($k, $v= null, $vType= '') {
        /* TODO: make into validation, so that this doesnt break tag processing
        switch ($k) {
            case 'name':
            case 'templatename':
                $v = str_replace($this->_invalidCharacters,'',$v);
                break;
            default: break;
        }
        */
        return parent::set($k,$v,$vType);
    }

    /**
     * Overrides xPDOObject::get to handle when retrieving the properties field
     * for an Element.
     *
     * {@inheritdoc}
     */
    public function get($k, $format= null, $formatTemplate= null) {
        $value = parent :: get($k, $format, $formatTemplate);
        if ($k === 'properties' && $this->xpdo instanceof modX && $this->xpdo->getParser() && empty($value)) {
            $value = !empty($this->properties) && is_string($this->properties)
                ? $this->xpdo->parser->parsePropertyString($this->properties)
                : null;
        }
        return $value;
    }

    /**
     * Overrides xPDOObject::remove to remove all Property Sets that are related
     * to this object.
     *
     * {@inheritdoc}
     */
    public function remove(array $ancestors= array ()) {
        $this->xpdo->removeCollection('modElementPropertySet', array('element' => $this->get('id'), 'element_class' => $this->_class));
        $result = parent :: remove($ancestors);
        return $result;
    }

    /**
     * Constructs a valid tag representation of the element.
     *
     * @access public
     * @return string A tag representation of the element.
     */
    public function getTag() {
        if (empty($this->_tag)) {
            $propTemp = array();
            if (empty($this->_propertyString) && !empty($this->_properties)) {
                while(list($key, $value) = each($this->_properties)) {
                    if (is_scalar($value)) {
                        $propTemp[] = trim($key) . '=`' . $value . '`';
                    }
                    else {
                        $propTemp[] = trim($key) . '=`' . md5(uniqid(rand(), true)) . '`';
                    }
                }
                if (!empty($propTemp)) {
                    $this->_propertyString = '?' . implode('&', $propTemp);
                }
            }
            $tag = '[[';
            $tag.= $this->getToken();
            $tag.= $this->get('name');
            if (!empty($this->_propertyString)) {
                $tag.= $this->_propertyString;
            }
            $tag.= ']]';
            $this->setTag($tag);
        }
        if (empty($this->_tag)) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Instance of ' . get_class($this) . ' produced an empty tag!');
        }
        return $this->_tag;
    }

    /**
     * Accessor method for the token class var.
     *
     * @return string The token for this element tag.
     */
    public function getToken() {
        return $this->_token;
    }

    /**
     * Setter method for the token class var.
     *
     * @param string $token The token to use for this element tag.
     */
    public function setToken($token) {
        $this->_token = $token;
    }

    /**
     * Setter method for the tag class var.
     *
     * @param string $tag The tag to use for this element.
     */
    public function setTag($tag) {
        $this->_tag = $tag;
    }


    /**
     * Process the element source content to produce a result.
     *
     * @abstract Implement this to define behavior for a MODx content element.
     * @access public
     * @param array|string $properties A set of configuration properties for the
     * element.
     * @param string $content Optional content to use in place of any persistent
     * content associated with the element.
     * @return mixed The result of processing.
     */
    public function process($properties= null, $content= null) {
        $this->xpdo->getParser();
        $this->getProperties($properties);
        $this->getTag();
        if ($this->xpdo->getDebug() === true) $this->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Processing Element: " . $this->get('name') . ($this->_tag ? "\nTag: {$this->_tag}" : "\n") . "\nProperties: " . print_r($this->_properties, true));
        if ($this->isCacheable() && isset ($this->xpdo->elementCache[$this->_tag])) {
            $this->_output = $this->xpdo->elementCache[$this->_tag];
            $this->_processed = true;
        } else {
	    $this->filterInput();
            $this->getContent(is_string($content) ? array('content' => $content) : array());
        }
        return $this->_result;
    }

    /**
     * Cache the current output of this element instance by tag signature.
     *
     * @access public
     */
    public function cache() {
        if ($this->isCacheable()) {
            $this->xpdo->elementCache[$this->_tag]= $this->_output;
        }
    }

    /**
     * Apply an input filter to an element.
     *
     * This is called by default in {@link modElement::process()} after the
     * element properties have been parsed.
     *
     * @access protected
     */
    public function filterInput() {
        $filter= null;
        if (!isset ($this->_filters['input']) || !($this->_filters['input'] instanceof modInputFilter)) {
            if (!$inputFilterClass= $this->get('input_filter')) {
                $inputFilterClass = $this->xpdo->getOption('input_filter',null,'filters.modInputFilter');
            }
            if ($filterClass= $this->xpdo->loadClass($inputFilterClass, '', false, true)) {
                if ($filter= new $filterClass($this->xpdo)) {
                    $this->_filters['input']= $filter;
                }
            }
        }
        if (isset ($this->_filters['input']) && $this->_filters['input'] instanceof modInputFilter) {
            $this->_filters['input']->filter($this);
        }
    }

    /**
     * Apply an output filter to an element.
     *
     * Call this method in your {modElement::process()} implementation when it
     * is appropriate, typically once all processing has been completed, but
     * before any caching takes place.
     *
     * @access protected
     */
    public function filterOutput() {
        $filter= null;
        if (!isset ($this->_filters['output']) || !($this->_filters['output'] instanceof modOutputFilter)) {
            if (!$outputFilterClass= $this->get('output_filter')) {
                $outputFilterClass = $this->xpdo->getOption('output_filter',null,'filters.modOutputFilter');
            }
            if ($filterClass= $this->xpdo->loadClass($outputFilterClass, '', false, true)) {
                if ($filter= new $filterClass($this->xpdo)) {
                    $this->_filters['output']= $filter;
                }
            }
        }
        if (isset ($this->_filters['output']) && $this->_filters['output'] instanceof modOutputFilter) {
            $this->_filters['output']->filter($this);
        }
    }

    /**
     * Loads the access control policies applicable to this element.
     *
     * {@inheritdoc}
     */
    public function findPolicy($context = '') {
        $policy = array();
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if (empty($this->_policies) || !isset($this->_policies[$context])) {
            $accessTable = $this->xpdo->getTableName('modAccessCategory');
            $policyTable = $this->xpdo->getTableName('modAccessPolicy');
            $categoryClosureTable = $this->xpdo->getTableName('modCategoryClosure');
            $sql = "SELECT `Acl`.`target`, `Acl`.`principal`, `Acl`.`authority`, `Acl`.`policy`, `Policy`.`data` FROM {$accessTable} `Acl` " .
                    "LEFT JOIN {$policyTable} `Policy` ON `Policy`.`id` = `Acl`.`policy` " .
                    "JOIN {$categoryClosureTable} `CategoryClosure` ON `CategoryClosure`.`descendant` = :category " .
                    "AND `Acl`.`principal_class` = 'modUserGroup' " .
                    "AND `CategoryClosure`.`ancestor` = `Acl`.`target` " .
                    "AND (`Acl`.`context_key` = :context OR `Acl`.`context_key` IS NULL OR `Acl`.`context_key` = '') " .
                    "GROUP BY `target`, `principal`, `authority`, `policy` " .
                    "ORDER BY `CategoryClosure`.`depth` DESC, `authority` ASC";
            $bindings = array(
                ':category' => $this->get('category'),
                ':context' => $context,
            );
            $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
            if ($query->stmt && $query->stmt->execute()) {
                while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $policy['modAccessCategory'][$row['target']][] = array(
                        'principal' => $row['principal'],
                        'authority' => $row['authority'],
                        'policy' => $row['data'] ? $this->xpdo->fromJSON($row['data'], true) : array(),
                    );
                }
            }
            $this->_policies[$context] = $policy;
        } else {
            $policy = $this->_policies[$context];
        }
        return $policy;
    }

    /**
     * Gets the raw, unprocessed source content for this element.
     *
     * @access public
     * @param array $options An array of options implementations can use to
     * accept language, revision identifiers, or other information to alter the
     * behavior of the method.
     * @return string The raw source content for the element.
     */
    public function getContent(array $options = array()) {
        if (!is_string($this->_content) || $this->_content === '') {
            if (isset($options['content'])) {
                $this->_content = $options['content'];
            } else {
                $this->_content = $this->get('content');
            }
        }
        return $this->_content;
    }

    /**
     * Set the raw source content for this element.
     *
     * @access public
     * @param mixed $content The source content; implementations can decide if
     * it can only be a string, or some other source from which to retrieve it.
     * @param array $options An array of options implementations can use to
     * accept language, revision identifiers, or other information to alter the
     * behavior of the method.
     * @return boolean True indicates the content was set.
     */
    public function setContent($content, array $options = array()) {
        return $this->set('content', $content);
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
        $set= $this->getPropertySet();
        if (!empty($set)) {
            $this->_properties= array_merge($this->_properties, $set);
        }
        if (!empty($properties)) {
            $this->_properties= array_merge($this->_properties, $this->xpdo->parser->parseProperties($properties));
        }
        return $this->_properties;
    }

    /**
     * Gets a named property set related to this element instance.
     *
     * If a setName parameter is not provided, this function will attempt to
     * extract a setName from the element name using the @ symbol to delimit the
     * name of the property set.
     *
     * Here is an example of an element tag using the @ modifier to specify a
     * property set name:
     *  [[ElementName@PropertySetName:FilterCommand=`FilterModifier`?
     *      &PropertyKey1=`PropertyValue1`
     *      &PropertyKey2=`PropertyValue2`
     *  ]]
     *
     * @access public
     * @param string|null $setName An explicit property set name to search for.
     * @return array|null An array of properties or null if no set is found.
     */
    public function getPropertySet($setName = null) {
        $propertySet= null;
        if ($setName === null) {
            $name = $this->get('name');
            $split= xPDO :: escSplit('@', $name);
            if ($split && isset($split[1])) {
                $name= $split[0];
                $setName= $split[1];
                $filters= xPDO :: escSplit(':', $setName);
                if ($filters && isset($filters[1]) && !empty($filters[1])) {
                    $setName= $filters[0];
                    $name.= ':' . $filters[1];
                }
                $this->set('name', $name);
            }
        }
        if (!empty($setName)) {
            $propertySetObj= $this->xpdo->getObjectGraph('modPropertySet', '{"Elements":{}}', array(
                'Elements.element' => $this->id,
                'Elements.element_class' => $this->_class,
                'modPropertySet.name' => $setName
            ));
            if ($propertySetObj) {
                $propertySet= $this->xpdo->parser->parseProperties($propertySetObj->get('properties'));
            }
        }
        return $propertySet;
    }

    /**
     * Set default properties for this element instance.
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
                    );
                }
                /* handle translations of properties (temp fix until modLocalizableObject in 2.1 and beyond) */
                if (!empty($propertyArray['lexicon'])) {
                    $this->xpdo->lexicon->load($propertyArray['lexicon']);
                    $propertyArray['desc'] = $this->xpdo->lexicon($propertyArray['desc']);

                    if (is_array($propertyArray['options'])) {
                        foreach ($propertyArray['options'] as $optionKey => &$option) {
                            if (!empty($option['text'])) {
                                $option['text'] = $this->xpdo->lexicon($option['text']);
                            }
                        }
                    }
                }
                $propertiesArray[$key] = $propertyArray;
            }

            if ($merge && !empty($propertiesArray)) {
                $existing = $this->get('properties');
                if (is_array($existing) && !empty($existing)) {
                    $propertyArray = array_merge($existing, $propertiesArray);
                }
            }
            $set = $this->set('properties', $propertiesArray);
        }
        return $set;
    }

    /**
     * Add a property set to this element, making it available for use.
     *
     * @access public
     * @param string|modPropertySet $propertySet A modPropertySet object or the
     * name of a modPropertySet object to create a relationship with.
     * @return boolean True if a relationship was created or already exists.
     */
    public function addPropertySet($propertySet) {
        $added= false;
        if (!empty($propertySet)) {
            if (is_string($propertySet)) {
                $propertySet = $this->xpdo->getObject('modPropertySet', array('name' => $propertySet));
            }
            if (is_object($propertySet) && $propertySet instanceof modPropertySet) {
                if (!$this->isNew() && !$propertySet->isNew() && $this->xpdo->getCount('modElementPropertySet', array('element' => $this->get('id'), 'element_class' => $this->_class, 'property_set' => $propertySet->get('id')))) {
                    $added = true;
                } else {
                    if ($propertySet->isNew()) $propertySet->save();
                    $link= $this->xpdo->newObject('modElementPropertySet');
                    $link->set('element', $this->get('id'));
                    $link->set('element_class', $this->_class);
                    $link->set('property_set', $propertySet->get('id'));
                    $added = $link->save();
                }
            }
        }
        return $added;
    }

    /**
     * Remove a property set from this element, making it unavailable for use.
     *
     * @access public
     * @param string|modPropertySet $propertySet A modPropertySet object or the
     * name of a modPropertySet object to dissociate from.
     * @return boolean True if a relationship was destroyed.
     */
    public function removePropertySet($propertySet) {
        $removed = false;
        if (!empty($propertySet)) {
            if (is_string($propertySet)) {
                $propertySet = $this->xpdo->getObject('modPropertySet', array('name' => $propertySet));
            }
            if (is_object($propertySet) && $propertySet instanceof modPropertySet) {
                $removed = $this->xpdo->removeObject('modElementPropertySet', array('element' => $this->get('id'), 'element_class' => $this->_class, 'property_set' => $propertySet->get('id')));
            }
        }
        return $removed;
    }

    /**
     * Indicates if the element is cacheable.
     *
     * @access public
     * @return boolean True if the element can be stored to or retrieved from
     * the element cache.
     */
    public function isCacheable() {
        return $this->_cacheable;
    }

    /**
     * Sets the runtime cacheability of the element.
     *
     * @access public
     * @param boolean $cacheable Indicates the value to set for cacheability of
     * this element.
     */
    public function setCacheable($cacheable = true) {
        $this->_cacheable = (boolean) $cacheable;
    }

    /**
     * Turns associative arrays into placeholders in the scope of this element.
     *
     * @access public
     * @param array $placeholders An associative array of placeholders to set.
     * @return array An array of placeholders overwritten from the containing
     * scope you can use to restore values from, or an empty array if no
     * placeholders were overwritten.
     */
    public function toPlaceholders($placeholders) {
        $restore = array();
        if (is_array($placeholders) && !empty($placeholders)) {
            $restoreKeys = array_keys($placeholders);
            foreach ($restoreKeys as $phKey) {
                if (isset($this->xpdo->placeholders[$phKey])) $restore[$phKey]= $this->xpdo->getPlaceholder($phKey);
            }
            $this->xpdo->toPlaceholders($placeholders);
        }
        return $restore;
    }
}
