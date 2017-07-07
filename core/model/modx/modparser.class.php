<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * Represents the MODX parser responsible for processing MODX tags.
 *
 * This class encapsulates all of the functions for collecting and evaluating
 * element tags embedded in text content.
 *
 * @package modx
 */
class modParser {
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx= null;
    /**
     * If the parser is currently processing a tag
     * @var bool $_processingTag
     */
    protected $_processingTag = false;
    /**
     * If the parser is currently processing an element
     * @var bool $_processingElement
     */
    protected $_processingElement = false;
    /**
     * If the parser is currently processing an uncacheable tag
     * @var bool $_processingUncacheable
     */
    protected $_processingUncacheable = false;
    /**
     * If the parser is currently removing all unprocessed tags
     * @var bool $_removingUnprocessed
     */
    protected $_removingUnprocessed = false;
    /**
     * If the parser has ever processed uncacheable
     *
     * @var bool $_startedProcessingUncacheable
     */
    protected $_startedProcessingUncacheable = false;

    /**
     * @param xPDO $modx A reference to the modX|xPDO instance
     */
    function __construct(xPDO &$modx) {
        $this->modx =& $modx;
    }

    /**
     * Returns true if the parser is currently processing an uncacheable tag
     * @return bool
     */
    public function isProcessingUncacheable() {
        $result = false;
        if ($this->isProcessingTag() || $this->isProcessingElement()) $result = (boolean) $this->_processingUncacheable;
        return $result;
    }

    /**
     * Returns true if the parser has ever processed an uncacheable tag
     * @return bool
     */
    public function startedProcessingUncacheable() {
        return $this->_startedProcessingUncacheable;
    }

    /**
     * Returns true if the parser is currently removing any unprocessed tags
     * @return bool
     */
    public function isRemovingUnprocessed() {
        $result = false;
        if ($this->isProcessingTag() || $this->isProcessingElement()) $result = (boolean) $this->_removingUnprocessed;
        return $result;
    }

    /**
     * Returns true if the parser is currently processing a tag
     * @return bool
     */
    public function isProcessingTag() {
        return (boolean) $this->_processingTag;
    }

    /**
     * Returns true if the parser is currently processing an element
     * @return bool
     */
    public function isProcessingElement() {
        return (boolean) $this->_processingElement;
    }

    public function setProcessingElement($arg = null) {
        if (is_bool($arg)) {
            $this->_processingElement = $arg;
        } elseif ($arg === null) {
            $this->_processingElement = !$this->_processingElement ? true : false;
        } else {
            $this->_processingElement = (boolean)$arg;
        }
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
     * @param string $content The content to process and act on (by reference).
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
     * @return int The number of processed tags
     */
    public function processElementTags($parentTag, & $content, $processUncacheable= false, $removeUnprocessed= false, $prefix= "[[", $suffix= "]]", $tokens= array (), $depth= 0) {
        if ($processUncacheable) {
            $this->_startedProcessingUncacheable = true;
        }
        $_processingTag = $this->_processingTag;
        $_processingUncacheable = $this->_processingUncacheable;
        $_removingUnprocessed = $this->_removingUnprocessed;
        $this->_processingTag = true;
        $this->_processingUncacheable = (boolean) $processUncacheable;
        $this->_removingUnprocessed = (boolean) $removeUnprocessed;
        $depth = $depth > 0 ? $depth - 1 : 0;
        $processed= 0;
        $tags= array ();
        /* invoke OnParseDocument event */
        $this->modx->documentOutput = $content;
        $this->modx->invokeEvent('OnParseDocument', array('content' => &$content));
        $content = $this->modx->documentOutput;
        unset($this->modx->documentOutput);
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
                    if ($tag[0] !== $tagOutput) $processed++;
                }
            }
            $this->mergeTagOutput($tagMap, $content);
            if ($processed > 0 && $depth > 0) {
                $processed+= $this->processElementTags($parentTag, $content, $processUncacheable, $removeUnprocessed, $prefix, $suffix, $tokens, $depth);
            }
        }

        $this->_removingUnprocessed = $_removingUnprocessed;
        $this->_processingUncacheable = $_processingUncacheable;
        $this->_processingTag = $_processingTag;
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
                foreach ($propSource as $propName => &$property) {
                    if (is_array($property) && array_key_exists('value', $property)) {
                        $properties[$propName]= $property['value'];
                    } else {
                        $properties[$propName]= &$property;
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
     * @return array The processed properties in array format
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
                if ($propValue[0] == '`' && $propValue[strlen($propValue) - 1] == '`') {
                    $propValue= substr($propValue, 1, strlen($propValue) - 2);
                }
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
     * @param boolean $processUncacheable
     * @return mixed The output of the processed element represented by the
     * specified tag.
     */
    public function processTag($tag, $processUncacheable = true) {
        $this->_processingTag = true;
        $element= null;
        $elementOutput= null;

        $outerTag= $tag[0];
        $innerTag= $tag[1];

        /* Avoid all processing for comment tags, e.g. [[- comments here]] */
        if (substr($innerTag, 0, 1) === '-') {
            return "";
        }

        /* collect any nested element tags in the innerTag and process them */
        $this->processElementTags($outerTag, $innerTag, $processUncacheable);
        $this->_processingTag = true;
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
                $this->_processingTag = false;
                return $outerTag;
            }
            $cacheable= false;
            $tokenOffset++;
            $token= substr($tagName, $tokenOffset, 1);
        }
        if ($cacheable && $token !== '+') {
            $elementOutput= $this->loadFromCache($outerTag);
        }
        $_restoreProcessingUncacheable = $this->_processingUncacheable;
        /* stop processing uncacheable tags so they are not cached in the cacheable content */
        if ($this->_processingUncacheable && $cacheable && $this->modx->getOption('parser_recurse_uncacheable', null, true)) {
            $this->_processingUncacheable = false;
        }
        if ($elementOutput === null) {
            switch ($token) {
                case '+':
                    $tagName= substr($tagName, 1 + $tokenOffset);
                    $element= new modPlaceholderTag($this->modx);
                    $element->set('name', $tagName);
                    $element->setTag($outerTag);
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
                    if ($element= $this->getElement('modChunk', $tagName)) {
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
                    elseif ($element= $this->getElement('modTemplateVar', $tagName)) {
                        $element->set('name', $tagName);
                        $element->setTag($outerTag);
                        $element->setCacheable($cacheable);
                        $elementOutput= $element->process($tagPropString);
                    }
                    break;
                default:
                    $tagName= substr($tagName, $tokenOffset);
                    if ($element= $this->getElement('modSnippet', $tagName)) {
                        $element->set('name', $tagName);
                        $element->setTag($outerTag);
                        $element->setCacheable($cacheable);
                        $elementOutput= $element->process($tagPropString);
                    }
            }
        }
        if (($elementOutput === null || $elementOutput === false) && $outerTag !== $tag[0]) {
            $elementOutput = $outerTag;
        }
        if ($this->modx->getDebug() === true) {
            $this->modx->log(xPDO::LOG_LEVEL_DEBUG, "Processing {$outerTag} as {$innerTag} using tagname {$tagName}:\n" . print_r($elementOutput, 1) . "\n\n");
            /* $this->modx->cacheManager->writeFile(MODX_BASE_PATH . 'parser.log', "Processing {$outerTag} as {$innerTag}:\n" . print_r($elementOutput, 1) . "\n\n", 'a'); */
        }
        $this->_processingTag = false;
        $this->_processingUncacheable = $_restoreProcessingUncacheable;
        return $elementOutput;
    }

    /**
     * Get a modElement instance taking advantage of the modX::$sourceCache.
     *
     * @param string $class The modElement derivative class to load.
     * @param string $name An element name or raw tagName to identify the modElement instance.
     * @return modElement|null An instance of the specified modElement derivative class.
     */
    public function getElement($class, $name) {
        $realname = $this->realname($name);
        if (array_key_exists($class, $this->modx->sourceCache) && array_key_exists($realname, $this->modx->sourceCache[$class])) {
            /** @var modElement $element */
            $element = $this->modx->newObject($class);
            $element->fromArray($this->modx->sourceCache[$class][$realname]['fields'], '', true, true);
            $element->setPolicies($this->modx->sourceCache[$class][$realname]['policies']);

            if (!empty($this->modx->sourceCache[$class][$realname]['source'])) {
                if (!empty($this->modx->sourceCache[$class][$realname]['source']['class_key'])) {
                    $sourceClassKey = $this->modx->sourceCache[$class][$realname]['source']['class_key'];
                    $this->modx->loadClass('sources.modMediaSource');
                    /* @var modMediaSource $source */
                    $source = $this->modx->newObject($sourceClassKey);
                    $source->fromArray($this->modx->sourceCache[$class][$realname]['source'],'',true,true);
                    $element->addOne($source,'Source');
                }
            }
        } else {
            /** @var modElement $element */
            $element = $this->modx->getObjectGraph($class,array('Source' => array()),array('name' => $realname), true);
            if ($element && array_key_exists($class, $this->modx->sourceCache)) {
                $this->modx->sourceCache[$class][$realname] = array(
                    'fields' => $element->toArray(),
                    'policies' => $element->getPolicies(),
                    'source' => $element->Source ? $element->Source->toArray() : array(),
                );
            }
            elseif(!$element) {
                $evtOutput = $this->modx->invokeEvent('OnElementNotFound', array('class' => $class, 'name' => $realname));
                $element = false;
                if ($evtOutput != false) {
                    foreach ((array) $evtOutput as $elm) {
                        if (!empty($elm) && is_string($elm)) {
                            $element = $this->modx->newObject($class, array(
                                'name' => $realname,
                                'snippet' => $elm
                            ));
                        }
                        elseif ($elm instanceof modElement ) {
                            $element = $elm;
                        }

                        if ($element) {
                            break;
                        }
                    }
                }
            }
        }
        if ($element instanceof modElement) {
            $element->set('name', $name);
        }
        return $element;
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
     * @param string $tag The tag signature representing the element instance.
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
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx= null;
    /**
     * The name of the tag
     * @var string $name
     */
    public $name;
    /**
     * The properties on the tag
     * @var array $properties
     */
    public $properties;
    /**
     * The content of the tag
     * @var string $_content
     */
    public $_content= null;
    /**
     * The processed output of the tag
     * @var string $_output
     */
    public $_output= '';
    /**
     * The result of processing the tag
     * @var bool $_result
     */
    public $_result= true;
    /**
     * Just the isolated properties part of the tag string
     * @var string $_propertyString
     */
    public $_propertyString= '';
    /**
     * The arranged properties array for this tag
     * @var array $_properties
     */
    public $_properties= array();
    /**
     * Whether or not the tag has been processed
     * @var boolean $_processed
     */
    public $_processed= false;
    /**
     * The tag string
     * @var string $_tag
     */
    public $_tag= '';
    /**
     * The tag initial token ($,%,*,etc)
     * @var string $_token
     */
    public $_token= '';
    /**
     * Fields on the tag
     * @var array $_fields
     */
    public $_fields= array(
        'name' => '',
        'properties' => ''
    );
    /**
     * Whether or not this tag is marked as cacheable
     * @var boolean $_cacheable
     */
    public $_cacheable= true;
    /**
     * Any output/input filters on this tag
     * @var array $_filters
     */
    public $_filters= array('input' => null, 'output' => null);

    /**
     * Set a reference to the modX object, load the name and properties, and instantiate the tag class instance.
     * @param modX $modx A reference to the modX object
     */
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
     *
     * @return string
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
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Instance of ' . get_class($this) . ' produced an empty tag!');
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
        $this->modx->parser->setProcessingElement(true);
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
     * Get an input filter instance configured for this Element.
     *
     * @return modInputFilter|null An input filter instance (or null if one cannot be loaded).
     */
    public function & getInputFilter() {
        if (!isset ($this->_filters['input']) || !($this->_filters['input'] instanceof modInputFilter)) {
            if (!$inputFilterClass= $this->get('input_filter')) {
                $inputFilterClass = $this->modx->getOption('input_filter',null,'filters.modInputFilter');
            }
            if ($filterClass= $this->modx->loadClass($inputFilterClass, '', false, true)) {
                if ($filter= new $filterClass($this->modx)) {
                    $this->_filters['input']= $filter;
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
    public function & getOutputFilter() {
        if (!isset ($this->_filters['output']) || !($this->_filters['output'] instanceof modOutputFilter)) {
            if (!$outputFilterClass= $this->get('output_filter')) {
                $outputFilterClass = $this->modx->getOption('output_filter',null,'filters.modOutputFilter');
            }
            if ($filterClass= $this->modx->loadClass($outputFilterClass, '', false, true)) {
                if ($filter= new $filterClass($this->modx)) {
                    $this->_filters['output']= $filter;
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
    public function filterInput() {
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
    public function filterOutput() {
        $filter = $this->getOutputFilter();
        if ($filter !== null && $filter instanceof modOutputFilter) {
            $filter->filter($this);
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
     *
     * @param string $content The content to set
     * @param array $options Ignored.
     * @return boolean
     */
    public function setContent($content, array $options = array()) {
        return $this->set('name', $content);
    }

    /**
     * Get the properties for this element instance for processing.
     *
     * @param array|string $properties An array or string of properties to apply.
     * @return array A simple array of properties ready to use for processing.
     */
    public function getProperties($properties = null) {
        $this->_properties= $this->modx->parser->parseProperties($this->get('properties'));
        $set= $this->getPropertySet();
        if (!empty($set)) {
            $this->_properties= array_merge($this->_properties, $set);
        }
        if (!empty($properties)) {
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
     * @return array|null An array of properties or null if no set is found.
     */
    public function getPropertySet($setName = null) {
        $propertySet= null;
        $name = $this->get('name');
        if (strpos($name, '@') !== false) {
            $psName= '';
            $split= xPDO :: escSplit('@', $name);
            if ($split && isset($split[1])) {
                $name= $split[0];
                $psName= $split[1];
                $filters= xPDO :: escSplit(':', $setName);
                if ($filters && isset($filters[1]) && !empty($filters[1])) {
                    $psName= $filters[0];
                    $name.= ':' . $filters[1];
                }
                $this->set('name', $name);
            }
            if (!empty($psName)) {
                $psObj= $this->modx->getObject('modPropertySet', array('name' => $psName));
                if ($psObj) {
                    $propertySet= $this->modx->parser->parseProperties($psObj->get('properties'));
                }
            }
        }
        if (!empty($setName)) {
            $propertySetObj= $this->modx->getObject('modPropertySet', array('name' => $setName));
            if ($propertySetObj) {
                if (is_array($propertySet)) {
                    $propertySet= array_merge($propertySet, $this->modx->parser->parseProperties($propertySetObj->get('properties')));
                } else {
                    $propertySet= $this->modx->parser->parseProperties($propertySetObj->get('properties'));
                }
            }
        }
        return $propertySet;
    }
}
/**
 * Tag representing a modResource field from the current MODX resource.
 *
 * [[*content]] Represents the content field from modResource.
 *
 * @uses modX::$resource The modResource instance being processed by modX.
 * @package modx
 */
class modFieldTag extends modTag {
    /**
     * Overrides modTag::__construct to set the Field Tag token
     * {@inheritdoc}
     */
    function __construct(modX & $modx) {
        parent :: __construct($modx);
        $this->setToken('*');
    }

    /**
     * Process the modFieldTag and return the output.
     *
     * {@inheritdoc}
     */
    public function process($properties= null, $content= null) {
        if ($this->get('name') === 'content') $this->setCacheable(false);
        parent :: process($properties, $content);
        if (!$this->_processed) {
            $this->_output= $this->_content;
            if (is_string($this->_output) && !empty ($this->_output)) {
                /* collect element tags in the content and process them */
                $maxIterations= intval($this->modx->getOption('parser_max_iterations',null,10));
                $this->modx->parser->processElementTags(
                    $this->_tag,
                    $this->_output,
                    $this->modx->parser->isProcessingUncacheable(),
                    $this->modx->parser->isRemovingUnprocessed(),
                    '[[',
                    ']]',
                    array(),
                    $maxIterations
                );
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
     *
     * {@inheritdoc}
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
    /**
     * Overrides modTag::__construct to set the Placeholder Tag token
     * {@inheritdoc}
     */
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
     *
     * {@inheritdoc}
     */
    public function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if (!$this->_processed) {
            $this->_output= $this->_content;
            if ($this->_output !== null && is_string($this->_output) && !empty($this->_output)) {
                    /* collect element tags in the content and process them */
                    $maxIterations= intval($this->modx->getOption('parser_max_iterations',null,10));
                    $this->modx->parser->processElementTags(
                        $this->_tag,
                        $this->_output,
                        $this->modx->parser->isProcessingUncacheable(),
                        $this->modx->parser->isRemovingUnprocessed(),
                        '[[',
                        ']]',
                        array(),
                        $maxIterations
                    );
                }
            if ($this->_output !== null || $this->modx->parser->startedProcessingUncacheable()) {
                $this->filterOutput();
                $this->_processed = true;
            }
        }
        /* finally, return the processed element content */
        return $this->_output;
    }

    /**
     * Get the raw source content of the field.
     *
     * {@inheritdoc}
     */
    public function getContent(array $options = array()) {
        if (!is_string($this->_content)) {
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
     *
     * {@inheritdoc}
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
    /**
     * Overrides modTag::__construct to set the Link Tag token
     * {@inheritdoc}
     */
    function __constructor(modX & $modx) {
        parent :: __construct($modx);
        $this->setToken('~');
    }

    /**
     * Processes the modLinkTag, recursively processing nested tags.
     *
     * {@inheritdoc}
     */
    public function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if (!$this->_processed) {
            $this->_output= $this->_content;
            if (is_string($this->_output) && !empty ($this->_output)) {
                /* collect element tags in the content and process them */
                $maxIterations= intval($this->modx->getOption('parser_max_iterations',null,10));
                $this->modx->parser->processElementTags(
                    $this->_tag,
                    $this->_output,
                    $this->modx->parser->isProcessingUncacheable(),
                    $this->modx->parser->isRemovingUnprocessed(),
                    '[[',
                    ']]',
                    array(),
                    $maxIterations
                );
                $context = '';
                if ($this->modx->getOption('friendly_urls', null, false)) {
                    if (array_key_exists('context', $this->_properties)) {
                        $context = $this->_properties['context'];
                    }
                    if ($context) {
                        $resource = $this->modx->findResource($this->_output, $context);
                        if ($resource) {
                            $this->_output = $resource;
                        }
                    }
                }
                if (!empty($this->_output)) {
                    $qs = '';
                    $scheme = $this->modx->getOption('link_tag_scheme',null,-1);
                    $options = array();
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
                        if (array_key_exists('use_weblink_target', $this->_properties)) {
                            $options['use_weblink_target'] = $this->_properties['use_weblink_target'];
                            unset($this->_properties['use_weblink_target']);
                        }
                        foreach ($this->_properties as $propertyKey => $propertyValue) {
                            if (in_array($propertyKey, array('context', 'scheme', 'use_weblink_target'))) continue;
                            $qs[]= "{$propertyKey}={$propertyValue}";
                        }
                        if ($qs= implode('&', $qs)) {
                            $qs= rawurlencode($qs);
                            $qs= str_replace(array('%26','%3D'),array('&amp;','='),$qs);
                        }
                    }
                    $this->_output= $this->modx->makeUrl($this->_output, $context, $qs, $scheme, $options);
                }
            }
            if (!empty($this->_output)) {
                $this->filterOutput();
                $this->cache();
                $this->_processed= true;
            }
            if (empty($this->_output)) {
                $this->modx->log(
                    modX::LOG_LEVEL_ERROR,
                    'Bad link tag `' . $this->_tag . '` encountered',
                    '',
                    $this->modx->resource
                        ? "resource {$this->modx->resource->id}"
                        : ($_SERVER['REQUEST_URI'] ? "uri {$_SERVER['REQUEST_URI']}" : '')
                );
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
    /**
     * Overrides modTag::__construct to set the Lexicon Tag token
     * {@inheritdoc}
     */
    function __construct(modX & $modx) {
        parent :: __construct($modx);
        $this->setToken('%');
    }

    /**
     * Processes a modLexiconTag, recursively processing nested tags.
     *
     * {@inheritdoc}
     */
    public function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if (!$this->_processed) {
            $this->_output= $this->_content;
            if (is_string($this->_output) && !empty ($this->_output)) {
                /* collect element tags in the content and process them */
                $maxIterations= intval($this->modx->getOption('parser_max_iterations',null,10));
                $this->modx->parser->processElementTags(
                    $this->_tag,
                    $this->_output,
                    $this->modx->parser->isProcessingUncacheable(),
                    $this->modx->parser->isRemovingUnprocessed(),
                    '[[',
                    ']]',
                    array(),
                    $maxIterations
                );
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
     *
     * {@inheritdoc}
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

                $this->_content= $this->modx->lexicon($this->get('name'), $this->_properties,$language);
            }
        }
        return $this->_content;
    }
}
