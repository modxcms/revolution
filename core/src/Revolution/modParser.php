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

use MODX\Revolution\Sources\modMediaSource;
use xPDO\xPDO;

/**
 * Represents the MODX parser responsible for processing MODX tags.
 *
 * This class encapsulates all of the functions for collecting and evaluating
 * element tags embedded in text content.
 *
 * @package MODX\Revolution
 */
class modParser
{
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
        if ($this->isProcessingTag() || $this->isProcessingElement()) $result = (bool) $this->_processingUncacheable;
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
        if ($this->isProcessingTag() || $this->isProcessingElement()) $result = (bool) $this->_removingUnprocessed;
        return $result;
    }

    /**
     * Returns true if the parser is currently processing a tag
     * @return bool
     */
    public function isProcessingTag() {
        return (bool) $this->_processingTag;
    }

    /**
     * Returns true if the parser is currently processing an element
     * @return bool
     */
    public function isProcessingElement() {
        return (bool) $this->_processingElement;
    }

    public function setProcessingElement($arg = null) {
        if (is_bool($arg)) {
            $this->_processingElement = $arg;
        } elseif ($arg === null) {
            $this->_processingElement = !$this->_processingElement ? true : false;
        } else {
            $this->_processingElement = (bool)$arg;
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
        $subPrefix = $prefix;
        $subSuffix = $suffix;

        if (strlen($prefix) % 2 === 0) {
            $uniqueCharactersPrefix = count_chars($prefix, 3);

            if (strlen($uniqueCharactersPrefix) === 1) {
                $subPrefix = substr($prefix, 0, 1);
            }
        }
        if (strlen($suffix) % 2 === 0) {
            $uniqueCharactersSuffix = count_chars($suffix, 3);

            if (strlen($uniqueCharactersSuffix) === 1) {
                $subSuffix = substr($suffix, 0, 1);
            }
        }

        $pattern = '/\Q'.$prefix.'\E((?:(?:[^'.$subSuffix.$subPrefix.']++[\s\S]*?|(?R))*?))\Q'.$suffix.'\E/x';
        preg_match_all($pattern, $origContent, $matches, PREG_SET_ORDER);
        if (preg_last_error() !== PREG_NO_ERROR) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Encountered a parser error: [' . preg_last_error() . '] ' . preg_last_error_msg());
        }

        $matchCount = count($matches);

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
    public function processElementTags($parentTag, & $content, $processUncacheable= false, $removeUnprocessed= false, $prefix= "[[", $suffix= "]]", $tokens= [], $depth= 0) {
        if ($processUncacheable) {
            $this->_startedProcessingUncacheable = true;
        }
        $_processingTag = $this->_processingTag;
        $_processingUncacheable = $this->_processingUncacheable;
        $_removingUnprocessed = $this->_removingUnprocessed;
        $this->_processingTag = true;
        $this->_processingUncacheable = (bool) $processUncacheable;
        $this->_removingUnprocessed = (bool) $removeUnprocessed;
        $depth = $depth > 0 ? $depth - 1 : 0;
        $processed= 0;
        $tags= [];
        /* invoke OnParseDocument event */
        $this->modx->documentOutput = $content;
        $this->modx->invokeEvent('OnParseDocument', ['content' => &$content]);
        $content = $this->modx->documentOutput;
        unset($this->modx->documentOutput);
        if ($processUncacheable) {
            $this->processConditionalBlocks($content);
        }
        if ($collected= $this->collectElementTags($content, $tags, $prefix, $suffix, $tokens)) {
            $tagMap= [];
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
     * Processes [[@if (expr)]]...[[@else]]...[[@endif]] blocks in content.
     * Replaces each block with the content or else branch based on the evaluated expression.
     *
     * @param string $content Content to process (by reference).
     */
    protected function processConditionalBlocks(&$content)
    {
        $prefix = '[[';
        $suffix = ']]';
        $ifOpen = '[[@if';
        $elseTag = '[[@else]]';
        $endifTag = '[[@endif]]';

        while (($ifPos = strpos($content, $ifOpen)) !== false) {
            $exprStart = $ifPos + strlen($ifOpen);
            $parenOpen = strpos($content, '(', $exprStart);
            if ($parenOpen === false || $parenOpen > $exprStart + 2) {
                break;
            }
            $parenClose = $this->findMatchingParen($content, $parenOpen);
            if ($parenClose === false) {
                break;
            }
            $expr = trim(substr($content, $parenOpen + 1, $parenClose - $parenOpen - 1));
            $tagEnd = strpos($content, $suffix, $parenClose);
            if ($tagEnd === false) {
                break;
            }
            $blockStart = $tagEnd + strlen($suffix);
            $depth = 1;
            $elsePos = false;
            $searchStart = $blockStart;
            $endifPos = false;

            while ($depth > 0) {
                $nextIf = strpos($content, $ifOpen, $searchStart);
                $nextElse = strpos($content, $elseTag, $searchStart);
                $nextEndif = strpos($content, $endifTag, $searchStart);
                $nearestIf = $nextIf !== false ? $nextIf : PHP_INT_MAX;
                $nearestEndif = $nextEndif !== false ? $nextEndif : PHP_INT_MAX;
                if ($nextElse !== false && $nextElse < $nearestIf && $nextElse < $nearestEndif && $depth === 1) {
                    $elsePos = $nextElse;
                    $searchStart = $nextElse + strlen($elseTag);
                    continue;
                }
                if ($nextIf !== false && ($nextEndif === false || $nextIf < $nextEndif)) {
                    $depth++;
                    $searchStart = $nextIf + strlen($ifOpen);
                    continue;
                }
                if ($nextEndif !== false) {
                    $depth--;
                    if ($depth === 0) {
                        $endifPos = $nextEndif;
                    }
                    $searchStart = $nextEndif + strlen($endifTag);
                    continue;
                }
                break;
            }

            if ($endifPos === false) {
                break;
            }

            $condition = $this->evaluateConditionalExpression($expr);
            if ($elsePos !== false) {
                $contentBlock = substr($content, $blockStart, $elsePos - $blockStart);
                $elseBlock = substr($content, $elsePos + strlen($elseTag), $endifPos - ($elsePos + strlen($elseTag)));
                $replacement = $condition ? $contentBlock : $elseBlock;
            } else {
                $contentBlock = substr($content, $blockStart, $endifPos - $blockStart);
                $replacement = $condition ? $contentBlock : '';
            }
            $fullBlock = substr($content, $ifPos, $endifPos - $ifPos + strlen($endifTag));
            $content = str_replace($fullBlock, $replacement, $content);
        }
    }

    /**
     * Finds the position of the closing parenthesis matching the open paren at $openPos.
     *
     * @param string $content
     * @param int $openPos
     * @return int|false
     */
    protected function findMatchingParen($content, $openPos)
    {
        $depth = 1;
        $len = strlen($content);
        for ($i = $openPos + 1; $i < $len; $i++) {
            $c = $content[$i];
            if ($c === '(') {
                $depth++;
            } elseif ($c === ')') {
                $depth--;
                if ($depth === 0) {
                    return $i;
                }
            }
        }
        return false;
    }

    /**
     * Evaluates a conditional expression using a whitelist (no eval).
     * Supports: $modx->user->id, $modx->resource->field, ==, !=, >, <, >=, <=, empty(), isset().
     *
     * @param string $expr Expression string, e.g. "$modx->user->id == 1"
     * @return bool
     */
    protected function evaluateConditionalExpression($expr)
    {
        $expr = trim($expr);
        if ($expr === '') {
            return false;
        }
        if (preg_match('/^empty\s*\(\s*(\$modx->(?:user|resource)(?:->\w+)*)\s*\)$/u', $expr, $m)) {
            $val = $this->getWhitelistedValue(trim($m[1]));
            return empty($val);
        }
        if (preg_match('/^isset\s*\(\s*(\$modx->(?:user|resource)(?:->\w+)*)\s*\)$/u', $expr, $m)) {
            $val = $this->getWhitelistedValue(trim($m[1]));
            return isset($val);
        }
        if (preg_match('/^(\$modx->(?:user|resource)(?:->\w+)*)\s*(===|!==|==|!=|>=|<=|>|<)\s*(.+)$/us', $expr, $m)) {
            $left = $this->getWhitelistedValue(trim($m[1]));
            $right = $this->parseConditionalComparisonValue(trim($m[3]));
            $op = $m[2];
            switch ($op) {
                case '==':
                    return $left == $right;
                case '===':
                    return $left === $right;
                case '!=':
                    return $left != $right;
                case '!==':
                    return $left !== $right;
                case '>':
                    return $left > $right;
                case '<':
                    return $left < $right;
                case '>=':
                    return $left >= $right;
                case '<=':
                    return $left <= $right;
                default:
                    return false;
            }
        }
        $val = $this->getWhitelistedValueFromExpression($expr);
        if ($val !== null) {
            return (bool) $val;
        }
        return false;
    }

    /**
     * Parses the right-hand side of a conditional comparison (number, quoted string, true/false/null).
     *
     * @param string $right Raw string from expression
     * @return mixed
     */
    protected function parseConditionalComparisonValue($right)
    {
        $len = strlen($right);
        $isQuoted = $len >= 2
            && (($right[0] === "'" && $right[$len - 1] === "'") || ($right[0] === '"' && $right[$len - 1] === '"'));
        if ($isQuoted) {
            $right = substr($right, 1, -1);
        }
        if (is_numeric($right)) {
            return strpos($right, '.') !== false ? (float) $right : (int) $right;
        }
        $lower = strtolower($right);
        if ($lower === 'true') {
            return true;
        }
        if ($lower === 'false') {
            return false;
        }
        if ($lower === 'null') {
            return null;
        }
        return $right;
    }

    /**
     * Returns the value of a whitelisted path like $modx->user->id or $modx->resource->pagetitle.
     *
     * @param string $path
     * @return mixed
     */
    protected function getWhitelistedValue($path)
    {
        return $this->getWhitelistedValueFromExpression($path);
    }

    /**
     * Resolves whitelisted variable path to value. Only $modx->user->* and $modx->resource->* allowed.
     *
     * @param string $expr
     * @return mixed|null
     */
    protected function getWhitelistedValueFromExpression($expr)
    {
        $expr = trim($expr);
        if (!preg_match('/^\$modx->(user|resource)(->\w+)*$/u', $expr)) {
            return null;
        }
        $parts = array_filter(explode('->', str_replace('$modx->', '', $expr)));
        if (empty($parts)) {
            return null;
        }
        $obj = $this->modx;
        foreach ($parts as $part) {
            if ($obj === null) {
                return null;
            }
            if (is_object($obj) && isset($obj->$part)) {
                $obj = $obj->$part;
            } elseif (is_array($obj) && array_key_exists($part, $obj)) {
                $obj = $obj[$part];
            } else {
                return null;
            }
        }
        return $obj;
    }

    /**
     * Returns true if the string looks like a JSON object or array (starts with { or [).
     *
     * @param string $string Property string to check.
     * @return bool
     */
    protected function isJsonPropertyString($string)
    {
        if (!is_string($string) || $string === '') {
            return false;
        }
        $trimmed = trim($string);
        $first = substr($trimmed, 0, 1);
        return $first === '{' || $first === '[';
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
        $properties= [];
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
     * Supports JSON object syntax, e.g. {"prop":"value","prop2":100}.
     *
     * @param string $string The property string to parse.
     * @param boolean $valuesOnly Indicates only the property value should be
     * returned.
     * @return array The processed properties in array format
     */
    public function parsePropertyString($string, $valuesOnly = false) {
        $properties = [];
        if ($this->isJsonPropertyString($string)) {
            $decoded = json_decode(trim($string), true);
            if (is_array($decoded)) {
                foreach ($decoded as $k => $v) {
                    if (is_scalar($v) || $v === null) {
                        if ($valuesOnly) {
                            $properties[$k] = $v;
                        } else {
                            $properties[$k] = [
                                'name' => $k,
                                'desc' => '',
                                'type' => 'textfield',
                                'options' => [],
                                'value' => $v,
                            ];
                        }
                    }
                }
                return $properties;
            }
        }
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
                $propOptions= [];
                $pvTmp= xPDO :: escSplit(';', $propValue);
                if ($pvTmp && isset ($pvTmp[1])) {
                    $propDesc= $pvTmp[0];
                    if (($pvTmp[1]=='list' || $pvTmp[1]=='combo') && isset($pvTmp[3]) && $pvTmp[3]) {
                        if (!$valuesOnly) {
                            $propType = modParser::_XType($pvTmp[1]);
                            $options = explode(',', $pvTmp[2]);
                            if ($options) {
                                foreach ($options as $option) $propOptions[] = ['name' => ucfirst($option), 'value' => $option];
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
                    $properties[$propName]= [
                        'name' => $propName,
                        'desc' => $propDesc,
                        'type' => $propType,
                        'options' => $propOptions,
                        'value' => $propValue
                    ];
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
                if (!in_array($xtype, ['checkbox', 'combo', 'datefield', 'numberfield', 'radio', 'textarea', 'textfield', 'timefield']
                )) {
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
        $tagPropString= '';
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
        } elseif (!$processUncacheable && strpos($tagPropString, '[[!') !== false) {
            $this->modx->log(xPDO::LOG_LEVEL_WARN, "You should not call uncached elements inside cached!\nOuter tag: {$tag[0]}\nInner tag {$innerTag}");
            $this->_processingTag = false;
            return $outerTag;
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
                case '-':
                    $elementOutput = '';
                    break;
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
                    if ($element= $this->getElement(modChunk::class, $tagName)) {
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
                    else {
                        $element = $this->getElement(modTemplateVar::class, $tagName);

                        // If our element tag was not found (e.i. not an existing TV), create a new instance of
                        // modFieldTag. We do this to make it possible to use output modifiers such as default. This
                        // mirrors the behavior of placeholders.
                        if ($element === false) {
                            $element = new modFieldTag($this->modx);
                        }

                        $element->set('name', $tagName);
                        $element->setTag($outerTag);
                        $element->setCacheable($cacheable);
                        $elementOutput= $element->process($tagPropString);
                    }
                    break;
                default:
                    $tagName= substr($tagName, $tokenOffset);
                    if ($element= $this->getElement(modSnippet::class, $tagName)) {
                        $element->set('name', $tagName);
                        $element->setTag($outerTag);
                        $element->setCacheable($cacheable);
                        $elementOutput= $element->process($tagPropString);
                    }
                    elseif(!empty($tagName)) {
                        if ($this->modx->getOption('log_snippet_not_found', null, false)) {
                            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "Could not find snippet with name {$tagName}.");
                        }
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
        $class = $this->modx->loadClass($class);
        if (array_key_exists($class, $this->modx->sourceCache) && array_key_exists($realname, $this->modx->sourceCache[$class])) {
            /** @var modElement $element */
            $element = $this->modx->newObject($class);
            $element->fromArray($this->modx->sourceCache[$class][$realname]['fields'], '', true, true);
            $element->setPolicies($this->modx->sourceCache[$class][$realname]['policies']);

            if (!empty($this->modx->sourceCache[$class][$realname]['source'])) {
                if (!empty($this->modx->sourceCache[$class][$realname]['source']['class_key'])) {
                    $sourceClassKey = $this->modx->sourceCache[$class][$realname]['source']['class_key'];
                    /* @var modMediaSource $source */
                    $source = $this->modx->newObject($sourceClassKey);
                    $source->fromArray($this->modx->sourceCache[$class][$realname]['source'],'',true,true);
                    $element->addOne($source,'Source');
                }
            }
        } else {
            /** @var modElement $element */
            $element = $this->modx->getObjectGraph($class, ['Source' => []], ['name' => $realname], true);
            if ($element && array_key_exists($class, $this->modx->sourceCache)) {
                $this->modx->sourceCache[$class][$realname] = [
                    'fields' => $element->toArray(),
                    'policies' => $element->getPolicies(),
                    'source' => $element->Source ? $element->Source->toArray() : [],
                ];
            }
            elseif(!$element) {
                $evtOutput = $this->modx->invokeEvent('OnElementNotFound', ['class' => $class, 'name' => $realname]);
                $element = false;
                if ($evtOutput != false) {
                    foreach ((array) $evtOutput as $elm) {
                        if (!empty($elm) && is_string($elm)) {
                            $element = $this->modx->newObject($class, [
                                'name' => $realname,
                                'snippet' => $elm
                            ]
                            );
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
