<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution;


use MODX\Revolution\Filters\modInputFilter;
use MODX\Revolution\Filters\modOutputFilter;
use xPDO\xPDO;

/**
 * Abstract class representing a pseudo-element that can be parsed.
 *
 * @abstract You must implement the process() method on derivatives to implement
 * a parseable element tag.  All element tags are identified by a unique single
 * character token at the beginning of the tag string.
 *
 * @package  MODX\Revolution
 */
abstract class modTag
{
    /**
     * A reference to the modX instance
     *
     * @var modX $modx
     */
    public $modx = null;
    /**
     * The name of the tag
     *
     * @var string $name
     */
    public $name;
    /**
     * The properties on the tag
     *
     * @var array $properties
     */
    public $properties;
    /**
     * The content of the tag
     *
     * @var string $_content
     */
    public $_content = null;
    /**
     * The processed output of the tag
     *
     * @var string $_output
     */
    public $_output = '';
    /**
     * The result of processing the tag
     *
     * @var bool $_result
     */
    public $_result = true;
    /**
     * Just the isolated properties part of the tag string
     *
     * @var string $_propertyString
     */
    public $_propertyString = '';
    /**
     * The arranged properties array for this tag
     *
     * @var array $_properties
     */
    public $_properties = [];
    /**
     * Whether or not the tag has been processed
     *
     * @var boolean $_processed
     */
    public $_processed = false;
    /**
     * The tag string
     *
     * @var string $_tag
     */
    public $_tag = '';
    /**
     * The tag initial token ($,%,*,etc)
     *
     * @var string $_token
     */
    public $_token = '';
    /**
     * Fields on the tag
     *
     * @var array $_fields
     */
    public $_fields = [
        'name' => '',
        'properties' => '',
    ];
    /**
     * Whether or not this tag is marked as cacheable
     *
     * @var boolean $_cacheable
     */
    public $_cacheable = true;
    /**
     * Any output/input filters on this tag
     *
     * @var array $_filters
     */
    public $_filters = ['input' => null, 'output' => null];

    /**
     * Set a reference to the modX object, load the name and properties, and instantiate the tag class instance.
     *
     * @param modX $modx A reference to the modX object
     */
    function __construct(modX &$modx)
    {
        $this->modx =& $modx;
        $this->name =& $this->_fields['name'];
        $this->properties =& $this->_fields['properties'];
    }

    /**
     * Generic getter method for modTag attributes.
     *
     * @see xPDOObject::get()
     *
     * @param string $k The field key.
     *
     * @return mixed The value of the field or null if it is not set.
     */
    public function get($k)
    {
        $value = null;
        if (array_key_exists($k, $this->_fields)) {
            if ($k == 'properties') {
                $value = is_string($this->_fields[$k]) && !empty($this->_fields[$k])
                    ? unserialize($this->_fields[$k])
                    : [];
            } else {
                $value = $this->_fields[$k];
            }
        }

        return $value;
    }

    /**
     * Generic setter method for modTag attributes.
     *
     * @see xPDOObject::set()
     *
     * @param string $k The field key.
     * @param mixed  $v The value to assign to the field.
     *
     * @return boolean
     */
    public function set($k, $v)
    {
        if ($k == 'properties') {
            $v = is_array($v) ? serialize($v) : $v;
        }
        $this->_fields[$k] = $v;

        return true;
    }

    /**
     * Cache the element into the elementCache by tag signature.
     *
     * @see modElement::cache()
     */
    public function cache()
    {
        if ($this->isCacheable()) {
            $this->modx->elementCache[$this->_tag] = $this->_output;
        }
    }

    /**
     * Returns the current token for the tag
     *
     * @return string The token for the tag
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * Setter method for the token class var.
     *
     * @param string $token The token to use for this element tag.
     */
    public function setToken($token)
    {
        $this->_token = $token;
    }

    /**
     * Setter method for the tag class var.
     *
     * @param string $tag The tag to use for this element.
     */
    public function setTag($tag)
    {
        $this->_tag = $tag;
    }

    /**
     * Gets a tag representation of the modTag instance.
     *
     * @return string
     */
    public function getTag()
    {
        if (empty($this->_tag) && ($name = $this->get('name'))) {
            $propTemp = [];
            if (empty($this->_propertyString) && !empty($this->_properties)) {
                foreach ($this->_properties as $key => $value) {
                    $propTemp[] = trim($key) . '=`' . $value . '`';
                }
                if (!empty($propTemp)) {
                    $this->_propertyString = '?' . implode('&', $propTemp);
                }
            }
            $tag = '[[';
            $tag .= $this->getToken();
            $tag .= $name;
            if (!empty($this->_propertyString)) {
                $tag .= $this->_propertyString;
            }
            $tag .= ']]';
            $this->_tag = $tag;
        }
        if (empty($this->_tag)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Instance of ' . get_class($this) . ' produced an empty tag!');
        }

        return $this->_tag;
    }

    /**
     * Process the tag and return the result.
     *
     * @see modElement::process()
     *
     * @param array|string $properties An array of properties or a formatted
     *                                 property string.
     * @param string       $content    Optional content to use for the element
     *                                 processing.
     *
     * @return mixed The result of processing the tag.
     */
    public function process($properties = null, $content = null)
    {
        $this->modx->getParser();
        $this->modx->parser->setProcessingElement(true);
        $this->getProperties($properties);
        $this->getTag();
        $this->filterInput();
        if ($this->modx->getDebug() === true) {
            $this->modx->log(xPDO::LOG_LEVEL_DEBUG,
                "Processing Element: " . $this->get('name') . ($this->_tag ? "\nTag: {$this->_tag}" : "\n") . "\nProperties: " . print_r($this->_properties,
                    true));
        }
        if ($this->isCacheable() && isset ($this->modx->elementCache[$this->_tag])) {
            $this->_output = $this->modx->elementCache[$this->_tag];
            $this->_processed = true;
        } else {
            $this->getContent(is_string($content) ? ['content' => $content] : []);
        }

        return $this->_result;
    }

    /**
     * Get an input filter instance configured for this Element.
     *
     * @return modInputFilter|null An input filter instance (or null if one cannot be loaded).
     */
    public function & getInputFilter()
    {
        if (!isset ($this->_filters['input']) || !($this->_filters['input'] instanceof modInputFilter)) {
            if (!$inputFilterClass = $this->get('input_filter')) {
                $inputFilterClass = $this->modx->getOption('input_filter', null, modInputFilter::class);
            }
            if ($filterClass = $this->modx->loadClass($inputFilterClass, '', false, true)) {
                if ($filter = new $filterClass($this->modx)) {
                    $this->_filters['input'] = $filter;
                }
            }
        }

        return $this->_filters['input'];
    }

    /**
     * Get an output filter instance configured for this Element.
     *
     * @return modOutputFilter|null An output filter instance (or null if one cannot be loaded).
     */
    public function & getOutputFilter()
    {
        if (!isset ($this->_filters['output']) || !($this->_filters['output'] instanceof modOutputFilter)) {
            if (!$outputFilterClass = $this->get('output_filter')) {
                $outputFilterClass = $this->modx->getOption('output_filter', null, modOutputFilter::class);
            }
            if ($filterClass = $this->modx->loadClass($outputFilterClass, '', false, true)) {
                if ($filter = new $filterClass($this->modx)) {
                    $this->_filters['output'] = $filter;
                }
            }
        }

        return $this->_filters['output'];
    }

    /**
     * Apply an input filter to a tag.
     *
     * This is called by default in {@link modTag::process()} after the tag
     * properties have been parsed.
     *
     * @see modElement::filterInput()
     */
    public function filterInput()
    {
        $filter = $this->getInputFilter();
        if ($filter !== null && $filter instanceof modInputFilter) {
            $filter->filter($this);
        }
    }

    /**
     * Apply an output filter to a tag.
     *
     * Call this method in your {modTag::process()} implementation when it is
     * appropriate, typically once all processing has been completed, but before
     * any caching takes place.
     *
     * @see modElement::filterOutput()
     */
    public function filterOutput()
    {
        $filter = $this->getOutputFilter();
        if ($filter !== null && $filter instanceof modOutputFilter) {
            $filter->filter($this);
        }
    }

    /**
     * Get the raw source content of the tag element.
     *
     * @param array $options An array of options implementations can use to
     *                       accept language, revision identifiers, or other information to alter the
     *                       behavior of the method.
     *
     * @return string The raw source content for the element.
     */
    public function getContent(array $options = [])
    {
        if (!$this->isCacheable() || !is_string($this->_content) || $this->_content === '') {
            if (isset($options['content'])) {
                $this->_content = $options['content'];
            } else {
                $this->_content = $this->get('name');
            }
        }

        return $this->_content;
    }

    /**
     * Set the raw source content for the tag element.
     *
     * @param string $content The content to set
     * @param array  $options Ignored.
     *
     * @return boolean
     */
    public function setContent($content, array $options = [])
    {
        return $this->set('name', $content);
    }

    /**
     * Get the properties for this element instance for processing.
     *
     * @param array|string $properties An array or string of properties to apply.
     *
     * @return array A simple array of properties ready to use for processing.
     */
    public function getProperties($properties = null)
    {
        $this->_properties = $this->modx->parser->parseProperties($this->get('properties'));
        $set = $this->getPropertySet();
        if (!empty($set)) {
            $this->_properties = array_merge($this->_properties, $set);
        }
        if (!empty($properties)) {
            $this->_properties = array_merge($this->_properties, $this->modx->parser->parseProperties($properties));
        }

        return $this->_properties;
    }

    /**
     * Set default properties for this element instance.
     *
     * @param array|string $properties A property array or property string.
     * @param boolean      $merge      Indicates if properties should be merged with
     *                                 existing ones.
     *
     * @return boolean true if the properties are set.
     */
    public function setProperties($properties, $merge = false)
    {
        $set = false;
        $propertyArray = [];
        if (is_string($properties)) {
            $properties = $this->modx->parser->parsePropertyString($properties);
        }
        if (is_array($properties)) {
            foreach ($properties as $propKey => $property) {
                if (is_array($property) && isset($property[5])) {
                    $propertyArray[$property[0]] = [
                        'name' => $property[0],
                        'desc' => $property[1],
                        'type' => $property[2],
                        'options' => $property[3],
                        'value' => $property[4],
                    ];
                } elseif (is_array($property) && isset($property['value'])) {
                    $propertyArray[$property['name']] = [
                        'name' => $property['name'],
                        'desc' => isset($property['description']) ? $property['description'] : (isset($property['desc']) ? $property['desc'] : ''),
                        'type' => isset($property['xtype']) ? $property['xtype'] : (isset($property['type']) ? $property['type'] : 'textfield'),
                        'options' => isset($property['options']) ? $property['options'] : [],
                        'value' => $property['value'],
                    ];
                } else {
                    $propertyArray[$propKey] = [
                        'name' => $propKey,
                        'desc' => '',
                        'type' => 'textfield',
                        'options' => [],
                        'value' => $property,
                    ];
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

    /**
     * Indicates if the element is cacheable.
     *
     * @return boolean True if the element can be stored to or retrieved from
     * the element cache.
     */
    public function isCacheable()
    {
        return $this->_cacheable;
    }

    /**
     * Sets the runtime cacheability of the element.
     *
     * @param boolean $cacheable Indicates the value to set for cacheability of
     *                           this element.
     */
    public function setCacheable($cacheable = true)
    {
        $this->_cacheable = (boolean)$cacheable;
    }

    /**
     * Gets a named property set to use with this modTag instance.
     *
     * This function will attempt to extract a setName from the tag name using the
     * @ symbol to delimit the name of the property set. If a setName parameter is provided,
     * the function will override any property set specified in the name by merging both
     * property sets.
     *
     * Here is an example of an tag using the @ modifier to specify a property set name:
     *  [[~TagName@PropertySetName:FilterCommand=`FilterModifier`?
     *      &PropertyKey1=`PropertyValue1`
     *      &PropertyKey2=`PropertyValue2`
     *  ]]
     *
     * @param string|null $setName An explicit property set name to search for.
     *
     * @return array|null An array of properties or null if no set is found.
     */
    public function getPropertySet($setName = null)
    {
        $propertySet = null;
        $name = $this->get('name');
        if (strpos($name, '@') !== false) {
            $psName = '';
            $split = xPDO:: escSplit('@', $name);
            if ($split && isset($split[1])) {
                $name = $split[0];
                $psName = $split[1];
                $filters = xPDO:: escSplit(':', $setName);
                if ($filters && isset($filters[1]) && !empty($filters[1])) {
                    $psName = $filters[0];
                    $name .= ':' . $filters[1];
                }
                $this->set('name', $name);
            }
            if (!empty($psName)) {
                $psObj = $this->modx->getObject(modPropertySet::class, ['name' => $psName]);
                if ($psObj) {
                    $propertySet = $this->modx->parser->parseProperties($psObj->get('properties'));
                }
            }
        }
        if (!empty($setName)) {
            $propertySetObj = $this->modx->getObject(modPropertySet::class, ['name' => $setName]);
            if ($propertySetObj) {
                if (is_array($propertySet)) {
                    $propertySet = array_merge($propertySet,
                        $this->modx->parser->parseProperties($propertySetObj->get('properties')));
                } else {
                    $propertySet = $this->modx->parser->parseProperties($propertySetObj->get('properties'));
                }
            }
        }

        return $propertySet;
    }
}
