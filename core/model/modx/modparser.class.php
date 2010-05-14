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
 * Represents the MODx parser responsible for processing MODx tags.
 *
 * This class encapsulates all of the functions for collecting and evaluating
 * element tags embedded in text content.
 *
 * @package modx
 */
class modParser {
    public $modx= null;

    function __construct(xPDO &$modx) {
        $this->modx =& $modx;
    }

    /**
     * Collects element tags in a string and injects them into an array.
     *
     * @param string $origContent The content to collect tags from.
     * @param array &$matches An array in which the collected tags will be
     * stored (by reference)
     * @param string $prefix The characters that define the start of a tag
     * (default= "[[").
     * @param string $suffix The characters that define the end of a tag
     * (default= "]]").
     * @return integer The number of tags collected from the content.
     */
    public function collectElementTags($origContent, array &$matches, $prefix= '[[', $suffix= ']]') {
        $matchCount= 0;
        if (!empty ($origContent) && is_string($origContent) && strpos($origContent, $prefix) !== false) {
            $openCount= 0;
            $offset= 0;
            $openPos= 0;
            $closePos= 0;
            if (($startPos= strpos($origContent, $prefix)) === false) {
                return $matchCount;
            }
            $offset= $startPos +strlen($prefix);
            if (($stopPos= strrpos($origContent, $suffix)) === false) {
                return $matchCount;
            }
            $stopPos= $stopPos + strlen($suffix);
            $length= $stopPos - $startPos;
            $content= $origContent;
            while ($length > 0) {
                $openCount= 0;
                $content= substr($content, $startPos);
                $openPos= 0;
                $offset= strlen($prefix);
                if (($closePos= strpos($content, $suffix, $offset)) === false) {
                    break;
                }
                $nextOpenPos= strpos($content, $prefix, $offset);
                while ($nextOpenPos !== false && $nextOpenPos < $closePos) {
                    $openCount++;
                    $offset= $nextOpenPos + strlen($prefix);
                    $nextOpenPos= strpos($content, $prefix, $offset);
                }
                $nextClosePos= strpos($content, $suffix, $closePos + strlen($suffix));
                while ($openCount > 0 && $nextClosePos !== false) {
                    $openCount--;
                    $closePos= $nextClosePos;
                    $nextOpenPos= strpos($content, $prefix, $offset);
                    while ($nextOpenPos !== false && $nextOpenPos < $closePos) {
                        $openCount++;
                        $offset= $nextOpenPos + strlen($prefix);
                        $nextOpenPos= strpos($content, $prefix, $offset);
                    }
                    $nextClosePos= strpos($content, $suffix, $closePos + strlen($suffix));
                }
                $closePos= $closePos +strlen($suffix);

                $outerTagLength= $closePos - $openPos;
                $innerTagLength= ($closePos -strlen($suffix)) - ($openPos +strlen($prefix));

                $matches[$matchCount][0]= substr($content, $openPos, $outerTagLength);
                $matches[$matchCount][1]= substr($content, ($openPos +strlen($prefix)), $innerTagLength);
                $matchCount++;

                if ($nextOpenPos === false) {
                    $nextOpenPos= strpos($content, $prefix, $closePos);
                }
                if ($nextOpenPos !== false) {
                    $startPos= $nextOpenPos;
                    $length= $length - $nextOpenPos;
                } else {
                    $length= 0;
                }
            }
        }
        if ($this->modx->getDebug() === true && !empty($matches)) {
            $this->modx->log(modX::LOG_LEVEL_DEBUG, "modParser::collectElementTags \$matches = " . print_r($matches, 1) . "\n");
            /* $this->modx->cacheManager->writeFile(MODX_CORE_PATH . 'logs/parser.log', print_r($matches, 1) . "\n", 'a'); */
        }
        return $matchCount;
    }

    /**
     * Collects and processes any set of tags as defined by a prefix and suffix.
     *
     * @param string $parentTag The tag representing the element processing this
     * tag.  Pass an empty string to allow parsing without this recursion check.
     * @param string &$content The content to process and act on (by reference).
     * @param boolean $processUncacheable Determines if noncacheable tags are to
     * be processed (default= false).
     * @param boolean $removeUnprocessed Determines if unprocessed tags should
     * be left in place in the content, or stripped out (default= false).
     * @param string $prefix The characters that define the start of a tag
     * (default= "[[").
     * @param string $suffix The characters that define the end of a tag
     * (default= "]]").
     * @param array $tokens Indicates that the parser should only process tags
     * with the tokens included in this array.
     * @param integer $depth The maximum iterations to recursively process tags
     * returned by prior passes, 0 by default.
     */
    public function processElementTags($parentTag, & $content, $processUncacheable= false, $removeUnprocessed= false, $prefix= "[[", $suffix= "]]", $tokens= array (), $depth= 0) {
        $depth = intval($depth);
        $depth = $depth > 0 ? $depth - 1 : 0;
        $processed= 0;
        $tags= array ();
        /* invoke OnParseDocument event */
        $this->modx->documentOutput = $content;      /* store source code so plugins can */
        $this->modx->invokeEvent('OnParseDocument');    /* work on it via $modx->documentOutput */
        $content = $this->modx->documentOutput;
        if ($collected= $this->collectElementTags($content, $tags, $prefix, $suffix, $tokens)) {
            $tagMap= array ();
            foreach ($tags as $tag) {
                $token= substr($tag[1], 0, 1);
                if (!$processUncacheable && $token === '!') {
                    if ($removeUnprocessed) {
                        $tagMap[$tag[0]]= '';
                    }
                }
                elseif (!empty ($tokens) && !in_array($token, $tokens)) {
                    $collected--;
                    continue;
                }
                if ($tag[0] === $parentTag) {
                    $tagMap[$tag[0]]= '';
                    $processed++;
                    continue;
                }
                $tagOutput= $this->processTag($tag, $processUncacheable);
                if (($tagOutput === null || $tagOutput === false) && $removeUnprocessed) {
                    $tagMap[$tag[0]]= '';
                    $processed++;
                }
                elseif ($tagOutput !== null && $tagOutput !== false) {
                    $tagMap[$tag[0]]= $tagOutput;
                    $processed++;
                }
            }
            $this->mergeTagOutput($tagMap, $content);
            if ($depth > 0) {
                $processed+= $this->processElementTags($parentTag, $content, $processUncacheable, $removeUnprocessed, $prefix, $suffix, $tokens, $depth);
            }
        }
        return $processed;
    }

    /**
     * Merges processed tag output into provided content string.
     *
     * @param array $tagMap An array with full tags as keys and processed output
     * as the values.
     * @param string $content The content to merge the tag output with (passed by
     * reference).
     */
    public function mergeTagOutput(array $tagMap, & $content) {
        if (!empty ($content) && is_array($tagMap) && !empty ($tagMap)) {
            $content= str_replace(array_keys($tagMap), array_values($tagMap), $content);
        }
    }

    /**
     * Parses an element/tag property string or array definition.
     *
     * @param string $propSource A valid property string or array source to
     * parse.
     * @return array An associative array of property values parsed from
     * the property string or array definition.
     */
    public function parseProperties($propSource) {
        $properties= array ();
        if (!empty ($propSource)) {
            if (is_string($propSource)) {
                $properties = $this->parsePropertyString($propSource, true);
            } elseif (is_array($propSource)) {
                foreach ($propSource as $propName => $property) {
                    if (is_array($property) && array_key_exists('value', $property)) {
                        $properties[$propName]= $property['value'];
                    } else {
                        $properties[$propName]= $property;
                    }
                }
            }
        }
        return $properties;
    }

    /**
     * Parses an element/tag property string and returns an array of properties.
     *
     * @param string $string The property string to parse.
     * @param boolean $valuesOnly Indicates only the property value should be
     * returned.
     */
    public function parsePropertyString($string, $valuesOnly = false) {
        $properties = array();
        $tagProps= xPDO :: escSplit("&", $string);
        foreach ($tagProps as $prop) {
            $property= xPDO :: escSplit('=', $prop);
            if (count($property) == 2) {
                $propName= $property[0];
                if (substr($propName, 0, 4) == "amp;") {
                    $propName= substr($propName, 4);
                }
                $propValue= $property[1];
                $propType= 'textfield';
                $propDesc= '';
                $propOptions= array();
                $pvTmp= xPDO :: escSplit(';', $propValue);
                if ($pvTmp && isset ($pvTmp[1])) {
                    $propDesc= $pvTmp[0];
                    if (($pvTmp[1]=='list' || $pvTmp[1]=='combo') && isset($pvTmp[3]) && $pvTmp[3]) {
                        if (!$valuesOnly) {
                            $propType = modParser::_XType($pvTmp[1]);
                            $options = explode(',', $pvTmp[2]);
                            if ($options) {
                                foreach ($options as $option) $propOptions[] = array('name' => ucfirst($option), 'value' => $option);
                            }
                        }
                        $propValue = $pvTmp[3];
                    }
                    elseif ($pvTmp[1]!='list' && $pvTmp[1]!='combo' && isset($pvTmp[2]) && $pvTmp[2]) {
                        if (!$valuesOnly) {
                            $propType = modParser::_XType($pvTmp[1]);
                        }
                        $propValue = $pvTmp[2];
                    } else {
                        $propValue = $pvTmp[0];
                    }
                }
                $propValue= trim($propValue, "`");
                $propValue= str_replace("``", "`", $propValue);
                if ($valuesOnly) {
                    $properties[$propName]= $propValue;
                } else {
                    $properties[$propName]= array(
                        'name' => $propName,
                        'desc' => $propDesc,
                        'type' => $propType,
                        'options' => $propOptions,
                        'value' => $propValue
                    );
                }
            }
        }
        return $properties;
    }

    /**
     * Converts legacy property string types to xtypes.
     *
     * @access protected
     * @param string $type A property type string.
     * @return string A valid xtype.
     */
    protected function _XType($type) {
        $xtype = $type;
        switch ($type) {
            case 'string':
                $xtype = 'textfield';
                break;
            case 'int':
            case 'integer':
            case 'float':
                $xtype = 'numberfield';
                break;
            case 'bool':
            case 'boolean':
                $xtype = 'checkbox';
                break;
            case 'list':
                break;
            default:
                if (!in_array($xtype, array('checkbox', 'combo', 'datefield', 'numberfield', 'radio', 'textarea', 'textfield', 'timefield'))) {
                    $xtype = 'textfield';
                }
                break;
        }
        return $xtype;
    }

    /**
     * Processes a modElement tag and returns the result.
     *
     * @param string $tag A full tag string parsed from content.
     * @return mixed The output of the processed element represented by the
     * specified tag.
     */
    public function processTag($tag, $processUncacheable = true) {
        $element= null;
        $elementOutput= null;

        $outerTag= $tag[0];
        $innerTag= $tag[1];

        /* collect any nested element tags in the innerTag and process them */
        $this->processElementTags($outerTag, $innerTag, true);
        $outerTag= '[[' . $innerTag . ']]';

        $tagParts= xPDO :: escSplit('?', $innerTag, '`', 2);
        $tagName= trim($tagParts[0]);
        $tagPropString= null;
        if (isset ($tagParts[1])) {
            $tagPropString= trim($tagParts[1]);
        }
        $token= substr($tagName, 0, 1);
        $tokenOffset= 0;
        $cacheable= true;
        if ($token === '!') {
            if (!$processUncacheable) {
                return $outerTag;
            }
            $cacheable= false;
            $tokenOffset++;
            $token= substr($tagName, $tokenOffset, 1);
        }
        if ($cacheable) {
            $elementOutput= $this->loadFromCache($outerTag);
        }
        if ($elementOutput === null) {
            switch ($token) {
                case '+':
                    $tagName= substr($tagName, 1 + $tokenOffset);
                    $element= new modPlaceholderTag($this->modx);
                    $element->set('name', $tagName);
                    $element->setTag($outerTag);
                    $element->setCacheable(false); /* placeholders cannot be cacheable! */
                    $elementOutput= $element->process($tagPropString);
                    break;
                case '%':
                    $tagName= substr($tagName, 1 + $tokenOffset);
                    $element= new modLexiconTag($this->modx);
                    $element->set('name', $tagName);
                    $element->setTag($outerTag);
                    $element->setCacheable($cacheable);
                    $elementOutput= $element->process($tagPropString);
                    break;
                case '~':
                    $tagName= substr($tagName, 1 + $tokenOffset);
                    $element= new modLinkTag($this->modx);
                    $element->set('name', $tagName);
                    $element->setTag($outerTag);
                    $element->setCacheable($cacheable);
                    $elementOutput= $element->process($tagPropString);
                    break;
                case '$':
                    $tagName= substr($tagName, 1 + $tokenOffset);
                    if ($element= $this->modx->getObject('modChunk', array ('name' => $this->realname($tagName)), true)) {
                        $element->set('name', $tagName);
                        $element->setTag($outerTag);
                        $element->setCacheable($cacheable);
                        $elementOutput= $element->process($tagPropString);
                    }
                    break;
                case '*':
                    $tagName= substr($tagName, 1 + $tokenOffset);
                    $nextToken= substr($tagName, 0, 1);
                    if ($nextToken === '#') {
                        $tagName= substr($tagName, 1);
                    }
                    if (is_array($this->modx->resource->_fieldMeta) && in_array($this->realname($tagName), array_keys($this->modx->resource->_fieldMeta))) {
                        $element= new modFieldTag($this->modx);
                        $element->set('name', $tagName);
                        $element->setTag($outerTag);
                        $element->setCacheable($cacheable);
                        $elementOutput= $element->process($tagPropString);
                    }
                    elseif ($element= $this->modx->getObject('modTemplateVar', array ('name' => $this->realname($tagName)), true)) {
                        $element->set('name', $tagName);
                        $element->setTag($outerTag);
                        $element->setCacheable($cacheable);
                        $elementOutput= $element->process($tagPropString);
                    }
                    break;
                default:
                    $tagName= substr($tagName, $tokenOffset);
                    if ($element= $this->modx->getObject('modSnippet', array ('name' => $this->realname($tagName)), true)) {
                        $element->set('name', $tagName);
                        $element->setTag($outerTag);
                        $element->setCacheable($cacheable);
                        $elementOutput= $element->process($tagPropString);
                    }
            }
        }
        if ($this->modx->getDebug() === true) {
            $this->modx->log(xPDO::LOG_LEVEL_DEBUG, "Processing {$outerTag} as {$innerTag} using tagname {$tagName}:\n" . print_r($elementOutput, 1) . "\n\n");
            /* $this->modx->cacheManager->writeFile(MODX_BASE_PATH . 'parser.log', "Processing {$outerTag} as {$innerTag}:\n" . print_r($elementOutput, 1) . "\n\n", 'a'); */
        }
        return $elementOutput;
    }

    /**
     * Gets the real name of an element containing filter modifiers.
     *
     * @param string $unfiltered The unfiltered name of a {@link modElement}.
     * @return string The name minus any filter modifiers.
     */
    public function realname($unfiltered) {
        $filtered= $unfiltered;
        $split= xPDO :: escSplit(':', $filtered);
        if ($split && isset($split[0])) {
            $filtered= $split[0];
            $propsetSplit = xPDO :: escSplit('@', $filtered);
            if ($propsetSplit && isset($propsetSplit[0])) {
                $filtered= $propsetSplit[0];
            }
        }
        return $filtered;
    }

    /**
     * Loads output cached by complete tag signature from the elementCache.
     *
     * @uses modX::$_elementCache Stores all cacheable content from processed
     * elements.
     * @param string tag The tag signature representing the element instance.
     * @return string The cached output from the element instance.
     */
    public function loadFromCache($tag) {
        $elementOutput= null;
        if (isset ($this->modx->elementCache[$tag])) {
            $elementOutput= (string) $this->modx->elementCache[$tag];
        }
        return $elementOutput;
    }
}

/**
 * Abstract class representing a pseudo-element that can be parsed.
 *
 * @abstract You must implement the process() method on derivatives to implement
 * a parseable element tag.  All element tags are identified by a unique single
 * character token at the beginning of the tag string.
 * @package modx
 */
abstract class modTag {
    public $modx= null;
    public $name;
    public $properties;
    public $_content= null;
    public $_output= '';
    public $_result= true;
    public $_propertyString= '';
    public $_properties= array ();
    public $_processed= false;
    public $_tag= '';
    public $_token= '';
    public $_fields= array (
        'name' => '',
        'properties' => ''
    );
    public $_cacheable= true;
    public $_filters= array ();

    function __construct(modX &$modx) {
        $this->modx =& $modx;
        $this->name =& $this->_fields['name'];
        $this->properties =& $this->_fields['properties'];
    }

    /**
     * Generic getter method for modTag attributes.
     *
     * @see xPDOObject::get()
     * @param string $k The field key.
     * @return mixed The value of the field or null if it is not set.
     */
    public function get($k) {
        $value = null;
        if (array_key_exists($k, $this->_fields)) {
            if ($k == 'properties') {
                $value = is_string($this->_fields[$k]) && !empty($this->_fields[$k])
                    ? unserialize($this->_fields[$k])
                    : array();
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
     * @param string $k The field key.
     * @param mixed $v The value to assign to the field.
     */
    public function set($k, $v) {
        if ($k == 'properties') {
            $v = is_array($v) ? serialize($v) : $v;
        }
        $this->_fields[$k]= $v;
    }
    /**
     * Cache the element into the elementCache by tag signature.
     * @see modElement::cache()
     */
    public function cache() {
        if ($this->isCacheable()) {
            $this->modx->elementCache[$this->_tag]= $this->_output;
        }
    }

    /**
     * Returns the current token for the tag
     *
     * @return string The token for the tag
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
     * Gets a tag representation of the modTag instance.
     */
    public function getTag() {
        if (empty($this->_tag) && ($name = $this->get('name'))) {
            $propTemp = array();
            if (empty($this->_propertyString) && !empty($this->_properties)) {
                while(list($key, $value) = each($this->_properties)) {
                    $propTemp[] = trim($key) . '=`' . $value . '`';
                }
                if (!empty($propTemp)) {
                    $this->_propertyString = '?' . implode('&', $propTemp);
                }
            }
            $tag = '[[';
            $tag.= $this->getToken();
            $tag.= $name;
            if (!empty($this->_propertyString)) {
                $tag.= $this->_propertyString;
            }
            $tag.= ']]';
            $this->_tag = $tag;
        }
        if (empty($this->_tag)) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Instance of ' . get_class($this) . ' produced an empty tag!');
        }
        return $this->_tag;
    }

    /**
     * Process the tag and return the result.
     *
     * @see modElement::process()
     * @param array|string $properties An array of properties or a formatted
     * property string.
     * @param string $content Optional content to use for the element
     * processing.
     * @return mixed The result of processing the tag.
     */
    public function process($properties= null, $content= null) {
        $this->modx->getParser();
        $this->getProperties($properties);
        $this->getTag();
        $this->filterInput();
        if ($this->modx->getDebug() === true) $this->modx->log(xPDO::LOG_LEVEL_DEBUG, "Processing Element: " . $this->get('name') . ($this->_tag ? "\nTag: {$this->_tag}" : "\n") . "\nProperties: " . print_r($this->_properties, true));
        if ($this->isCacheable() && isset ($this->modx->elementCache[$this->_tag])) {
            $this->_output = $this->modx->elementCache[$this->_tag];
            $this->_processed = true;
        } else {
            $this->getContent(is_string($content) ? array('content' => $content) : array());
        }
        return $this->_result;
    }

    /**
     * Apply an input filter to a tag.
     *
     * This is called by default in {@link modTag::process()} after the tag
     * properties have been parsed.
     *
     * @see modElement::filterInput()
     */
    public function filterInput() {
        $filter= null;
        if (!isset ($this->_filters['input'])) {
            if (!$inputFilterClass= $this->get('input_filter')) {
                $inputFilterClass= $this->modx->getOption('input_filter',null,'filters.modInputFilter');
            }
            if ($filterClass= $this->modx->loadClass($inputFilterClass, '', false, true)) {
                if ($filter= new $filterClass($this->modx)) {
                    $this->_filters['input']= $filter;
                }
            }
        }
        if (isset ($this->_filters['input']) && $this->_filters['input'] instanceof modInputFilter) {
            $this->_filters['input']->filter($this);
        }
    }
    /**
     * Apply an output filter to a tag.
     *
     * Call this method in your {modTag::process()} implementation when it is
     * appropriate, typically once all processing has been completed, but before
     * any caching takes place.
     */
    public function filterOutput() {
        $filter= null;
        if (!isset ($this->_filters['output'])) {
            if (!$outputFilterClass= $this->get('output_filter')) {
                $outputFilterClass= $this->modx->getOption('output_filter',null,'filters.modOutputFilter');
            }
            if ($filterClass= $this->modx->loadClass($outputFilterClass, '', false, true)) {
                if ($filter= new $filterClass($this->modx)) {
                    $this->_filters['output']= $filter;
                }
            }
        }
        if (isset ($this->_filters['output']) && $this->_filters['output'] instanceof modOutputFilter) {
            $this->_filters['output']->filter($this);
        }
    }

    /**
     * Get the raw source content of the tag element.
     *
     * @param array $options An array of options implementations can use to
     * accept language, revision identifiers, or other information to alter the
     * behavior of the method.
     * @return string The raw source content for the element.
    */
    public function getContent(array $options = array()) {
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
     */
    public function setContent($content, array $options = array()) {
        return $this->set('name', $content);
    }

    /**
     * Get the properties for this element instance for processing.
     *
     * @param array|string $properties An array or string of properties to
     * apply.
     * @return array A simple array of properties ready to use for processing.
     */
    public function getProperties($properties = null) {
        $this->_properties= $this->modx->parser->parseProperties($this->get('properties'));
        if ($properties !== null && !empty($properties)) {
            $this->_properties= array_merge($this->_properties, $this->modx->parser->parseProperties($properties));
        }
        return $this->_properties;
    }

    /**
     * Set default properties for this element instance.
     *
     * @param array|string $properties A property array or property string.
     * @param boolean $merge Indicates if properties should be merged with
     * existing ones.
     * @return boolean true if the properties are set.
     */
    public function setProperties($properties, $merge = false) {
        $set = false;
        $propertyArray = array();
        if (is_string($properties)) {
            $properties = $this->modx->parser->parsePropertyString($properties);
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

    /**
     * Indicates if the element is cacheable.
     *
     * @return boolean True if the element can be stored to or retrieved from
     * the element cache.
     */
    public function isCacheable() {
        return $this->_cacheable;
    }

    /**
     * Sets the runtime cacheability of the element.
     *
     * @param boolean $cacheable Indicates the value to set for cacheability of
     * this element.
     */
    public function setCacheable($cacheable = true) {
        $this->_cacheable = (boolean) $cacheable;
    }
}
/**
 * Tag representing a modResource field from the current MODx resource.
 *
 * [[*content]] Represents the content field from modResource.
 *
 * @uses modX::$resource The modResource instance being processed by modX.
 * @package modx
 */
class modFieldTag extends modTag {
    function __construct(modX & $modx) {
        parent :: __construct($modx);
        $this->setToken('*');
    }

    /**
     * Process the modFieldTag and return the output.
     */
    public function process($properties= null, $content= null) {
        if ($this->get('name') === 'content') $this->setCacheable(false);
        parent :: process($properties, $content);
        if (!$this->_processed) {
            $this->_output= $this->_content;
            if (is_string($this->_output) && !empty ($this->_output)) {
                /* collect element tags in the content and process them */
                $maxIterations= intval($this->modx->getOption('parser_max_iterations',null,10));
                $this->modx->parser->processElementTags($this->_tag, $this->_output, false, false, '[[', ']]', array(), $maxIterations);
            }
            $this->filterOutput();
            $this->cache();
            $this->_processed= true;
        }
        /* finally, return the processed element content */
        return $this->_output;
    }

    /**
     * Get the raw source content of the field.
     */
    public function getContent(array $options = array()) {
        if (!$this->isCacheable() || !is_string($this->_content) || $this->_content === '') {
            if (isset($options['content']) && !empty($options['content'])) {
                $this->_content = $options['content'];
            } else {
                if ($this->get('name') == 'content') {
                    $this->_content = $this->modx->resource->getContent($options);
                } else {
                    $this->_content = $this->modx->resource->get($this->get('name'));
                }
            }
        }
        return $this->_content;
    }
}

/**
 * Represents placeholder tags.
 *
 * [[+placeholder_key]] Represents a placeholder with name placeholder_key.
 *
 * @uses modX::getPlaceholder() To retrieve the placeholder value.
 * @package modx
 */
class modPlaceholderTag extends modTag {
    function __construct(modX & $modx) {
        parent :: __construct($modx);
        $this->setCacheable(false);
        $this->setToken('+');
    }

    /**
     * Processes the modPlaceholderTag, recursively processing nested tags.
     *
     * Tags in the properties of the tag itself, or the content returned by the
     * tag element are processed.  Non-cacheable nested tags are only processed
     * if this tag element is also non-cacheable.
     */
    public function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if (!$this->_processed) {
            $this->_output= $this->_content;
            if (is_string($this->_output) && !empty ($this->_output)) {
                /* collect element tags in the content and process them */
                $maxIterations= intval($this->modx->getOption('parser_max_iterations',null,10));
                $this->modx->parser->processElementTags($this->_tag, $this->_output, false, false, '[[', ']]', array(), $maxIterations);
            }
            $this->filterOutput();
            $this->cache();
            $this->_processed= true;
        }
        /* finally, return the processed element content */
        return $this->_output;
    }

    /**
     * Get the raw source content of the field.
     */
    public function getContent(array $options = array()) {
        if (!is_string($this->_content) || $this->_content === '') {
            if (isset($options['content'])) {
                $this->_content = $options['content'];
            } else {
                $this->_content = $this->modx->getPlaceholder($this->get('name'));
            }
        }
        return $this->_content;
    }

    /**
     * modPlaceholderTag instances cannot be cacheable.
     *
     * @return boolean Always returns false.
     */
    public function isCacheable() {
        return false;
    }

    /**
     * modPlaceholderTag instances cannot be cacheable.
     */
    public function setCacheable($cacheable = true) {}
}

/**
 * Represents link tags.
 *
 * [[~12]] Creates a URL from the specified resource identifier.
 *
 * @package modx
 */
class modLinkTag extends modTag {
    function __constructor(modX & $modx) {
        parent :: __construct($modx);
        $this->setToken('~');
    }

    /**
     * Processes the modLinkTag, recursively processing nested tags.
     */
    public function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if (!$this->_processed) {
            $this->_output= $this->_content;
            if (is_string($this->_output) && !empty ($this->_output)) {
                /* collect element tags in the content and process them */
                $maxIterations= intval($this->modx->getOption('parser_max_iterations',null,10));
                $this->modx->parser->processElementTags($this->_tag, $this->_output, false, false, '[[', ']]', array(), $maxIterations);
                if (isset ($this->modx->aliasMap[$this->_output])) {
                    $this->_output= $this->modx->aliasMap[$this->_output];
                }
                if (!empty($this->_output)) {
                    $qs = '';
                    $context = '';
                    $scheme = -1;
                    if (is_array($this->_properties) && !empty($this->_properties)) {
                        $qs = array();
                        if (array_key_exists('context', $this->_properties)) {
                            $context = $this->_properties['context'];
                            unset($this->_properties['context']);
                        }
                        if (array_key_exists('scheme', $this->_properties)) {
                            $scheme = $this->_properties['scheme'];
                            unset($this->_properties['scheme']);
                            if (is_numeric($scheme)) $scheme = (integer) $scheme;
                        }
                        foreach ($this->_properties as $propertyKey => $propertyValue) {
                            if (in_array($propertyKey, array('context', 'scheme'))) continue;
                            $qs[]= "{$propertyKey}={$propertyValue}";
                        }
                        if ($qs= implode('&', $qs)) {
                            $qs= urlencode($qs);
                            $qs= str_replace(array('%26','%3D'),array('&amp;','='),$qs);
                        }
                    }
                    $this->_output= $this->modx->makeUrl($this->_output, $context, $qs, $scheme);
                }
            }
            if (!empty($this->_output)) {
                $this->filterOutput();
                $this->cache();
                $this->_processed= true;
            }
        }
        /* finally, return the processed element content */
        return $this->_output;
    }
}

/**
 * Represents Lexicon tags, for localized strings.
 *
 * [[%word_or_phase]] Returns the lexicon representation of 'word_or_phrase' for
 * the currently loaded language.
 *
 * @package modx
 */
class modLexiconTag extends modTag {
    function __construct(modX & $modx) {
        parent :: __construct($modx);
        $this->setToken('%');
    }

    /**
     * Processes a modLexiconTag, recursively processing nested tags.
     */
    public function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if (!$this->_processed) {
            $this->_output= $this->_content;
            if (is_string($this->_output) && !empty ($this->_output)) {
                /* collect element tags in the content and process them */
                $maxIterations= intval($this->modx->getOption('parser_max_iterations',null,10));
                $this->modx->parser->processElementTags($this->_tag, $this->_output, false, false, '[[', ']]', array(), $maxIterations);
            }
            $this->filterOutput();
            $this->cache();
            $this->_processed= true;
        }
        /* finally, return the processed element content */
        return $this->_output;
    }

    /**
     * Get the raw source content of the link.
     */
    public function getContent(array $options = array()) {
        if (!is_string($this->_content) || $this->_content === '') {
            if (isset($options['content'])) {
                $this->_content = $options['content'];
            } else {
                if (!is_object($this->modx->lexicon)) {
                    $this->modx->getService('lexicon','modLexicon');
                }
                $topic = !empty($this->_properties['topic']) ? $this->_properties['topic'] : 'default';
                $namespace = !empty($this->_properties['namespace']) ? $this->_properties['namespace'] : 'core';
                $language = !empty($this->_properties['language']) ? $this->_properties['language'] : $this->modx->getOption('cultureKey',null,'en');
                $this->modx->lexicon->load($language.':'.$namespace.':'.$topic);

                $this->_content= $this->modx->lexicon($this->get('name'), $this->_properties);
            }
        }
        return $this->_content;
    }
}
