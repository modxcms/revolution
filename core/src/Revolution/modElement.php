<?php

namespace MODX\Revolution;

use MODX\Revolution\Filters\modInputFilter;
use MODX\Revolution\Filters\modOutputFilter;
use MODX\Revolution\Sources\modFileMediaSource;
use MODX\Revolution\Sources\modMediaSource;
use MODX\Revolution\Sources\modMediaSourceElement;
use MODX\Revolution\Sources\modMediaSourceInterface;
use PDO;
use xPDO\Om\xPDOCriteria;
use xPDO\xPDO;

/**
 * Represents an element of source content managed by MODX.
 *
 * These elements are defined by some type of source content that when processed
 * will provide output or some type of logical result based on mutable
 * properties.
 *
 * This class creates an instance of a modElement object. This should not be
 * called directly, but rather extended for derivative modElement classes.
 *
 * @property int  $source
 * @property bool $property_preprocess
 *
 * @property modAccessElement[] $Acls
 *
 * @package MODX\Revolution
 */
class modElement extends modAccessibleSimpleObject
{
    /**
     * The property value array for the element.
     *
     * @var array
     */
    public $_properties = null;
    /**
     * The string representation of the element properties.
     *
     * @var string
     */
    public $_propertyString = '';
    /**
     * The source content of the element.
     *
     * @var string
     */
    public $_content = '';
    /**
     * The source of the element.
     *
     * @var string
     */
    public $_source = null;
    /**
     * The output of the element.
     *
     * @var string
     */
    public $_output = '';
    /**
     * The bool result of the element.
     *
     * This is typically only applicable to elements that use PHP source content.
     *
     * @var bool
     */
    public $_result = true;
    /**
     * The tag signature of the element instance.
     *
     * @var string
     */
    public $_tag = null;
    /**
     * The character token which helps identify the element class in tag string.
     *
     * @var string
     */
    public $_token = '';
    /**
     * @var bool If the element is cacheable or not.
     */
    public $_cacheable = true;
    /**
     * @var bool Indicates if the element was processed already.
     */
    public $_processed = false;
    /**
     * @var array Optional filters that can be used during processing.
     */
    public $_filters = ['input' => null, 'output' => null];

    /**
     * @var string Path to source file location when modElement->isStatic() === true.
     */
    protected $_sourcePath = "";
    /**
     * @var string Source file name when modElement->isStatic() === true.
     */
    protected $_sourceFile = "";
    /**
     * @var array A list of invalid characters in the name of an Element.
     */
    protected $_invalidCharacters = [
        '!',
        '@',
        '#',
        '$',
        '%',
        '^',
        '&',
        '*',
        '(',
        ')',
        '+',
        '=',
        '[',
        ']',
        '{',
        '}',
        '\'',
        '"',
        ';',
        ':',
        '\\',
        '/',
        '<',
        '>',
        '?'
        ,
        ' ',
        ',',
        '`',
        '~',
    ];

    /**
     * Provides custom handling for retrieving the properties field of an Element.
     *
     * {@inheritdoc}
     */
    public function get($k, $format = null, $formatTemplate = null)
    {
        $value = parent:: get($k, $format, $formatTemplate);
        if ($k === 'properties' && $this->xpdo instanceof modX && $this->xpdo->getParser() && empty($value)) {
            $value = !empty($this->properties) && is_string($this->properties)
                ? $this->xpdo->parser->parsePropertyString($this->properties)
                : null;
        }
        /* automatically translate lexicon descriptions */
        if ($k == 'properties' && !empty($value) && is_array($value) && is_object($this->xpdo) && $this->xpdo instanceof modX && $this->xpdo->lexicon) {
            foreach ($value as &$property) {
                if (!empty($property['lexicon'])) {
                    if (strpos($property['lexicon'], ':') !== false) {
                        $this->xpdo->lexicon->load('en:' . $property['lexicon']);
                    } else {
                        $this->xpdo->lexicon->load('en:core:' . $property['lexicon']);
                    }
                    $this->xpdo->lexicon->load($property['lexicon']);
                }
                $property['desc_trans'] = $this->xpdo->lexicon($property['desc']);
                $property['area'] = !empty($property['area']) ? $property['area'] : '';
                $property['area_trans'] = !empty($property['area']) ? $this->xpdo->lexicon($property['area']) : '';

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
     * Overridden to handle changes to content managed in an external file.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag = null)
    {
        if (!$this->getOption(xPDO::OPT_SETUP)) {
            if ($this->staticSourceChanged()) {
                $staticContent = $this->getFileContent();
                if ($staticContent !== $this->get('content')) {
                    if ($this->isStaticSourceMutable() && $staticContent === '') {
                        $this->setDirty('content');
                    } else {
                        $this->setContent($staticContent);
                    }
                }
                unset($staticContent);
            }

            $staticContentChanged = $this->staticContentChanged();
            if ($staticContentChanged && !$this->isStaticSourceMutable()) {
                $this->setContent($this->getFileContent());
                $staticContentChanged = false;
            }

            /* If element is empty, set to true in order to create an empty static file. */
            $content = $this->get('content');
            if (empty($content) && $this->isStatic()) {
                $staticContentChanged = true;
            }
        }

        /* Set oldPath before saving object. */
        $oldPath = $this->getOldStaticFilePath();

        $saved = parent::save($cacheFlag);
        if (!$this->getOption(xPDO::OPT_SETUP)) {
            if ($saved && $staticContentChanged) {
                $saved = $this->setFileContent($this->get('content'));
            }
        }

        /* Removing old static file when succesfull saved and oldPath has been set. */
        if ($saved && $oldPath && $this->isStaticFilesAutomated()) {
            if (@unlink($oldPath)) {
                $pathinfo = pathinfo($oldPath);
                $this->cleanupStaticFileDirectories($pathinfo['dirname']);
            }
        }

        return $saved;
    }

    /**
     * Determine if static files should be automated for current element class.
     *
     * @return bool
     */
    protected function isStaticFilesAutomated()
    {
        $elements = [
            modTemplate::class => 'templates',
            modTemplateVar::class => 'tvs',
            modChunk::class => 'chunks',
            modSnippet::class => 'snippets',
            modPlugin::class => 'plugins',
        ];

        if (!array_key_exists($this->_class, $elements)) {
            return false;
        }

        return (bool)$this->xpdo->getOption('static_elements_automate_' . $elements[$this->_class], null, false);
    }

    /**
     * Constructs a valid tag representation of the element.
     *
     * @return string A tag representation of the element.
     */
    public function getTag()
    {
        if (empty($this->_tag)) {
            if (empty($this->_propertyString) && !empty($this->_properties)) {
                $propTemp = [];
                foreach ($this->_properties as $key => $value) {
                    $key = trim($key);
                    if (is_scalar($value)) {
                        $propTemp[$key] = $key . '=`' . $value . '`';
                    } else {
                        if (is_array($value)) {
                            $func = function ($item) use (&$func) {
                                if (is_array($item)) {
                                    return array_map($func, $item);
                                } elseif ($item instanceof \xPDOObject) {
                                    return $item->toArray('', false, true);
                                } else {
                                    return $item;
                                }
                            };
                            $value = array_map($func, $value);
                        } elseif ($value instanceof \xPDOObject) {
                            $value = $value->toArray('', false, true);
                        }
                        try {
                            $propTemp[$key] = $key . '=`' . (is_array($value) ? md5(serialize($value))
                                    : md5(uniqid(rand(), true))) . '`';
                        } catch (\Throwable $handlerException) {
                            $propTemp[$key] = $key . '=`' . md5(uniqid(rand(), true)) . '`';
                        }
                    }
                }
                if (!empty($propTemp)) {
                    ksort($propTemp);
                    $this->_propertyString = '?' . implode('&', $propTemp);
                }
            }
            $tag = '[[';
            $tag .= $this->getToken();
            $tag .= strlen($this->get('name')) > 0 ? $this->get('name') : md5(uniqid(rand()));
            if (!empty($this->_propertyString)) {
                $tag .= $this->_propertyString;
            }
            $tag .= ']]';
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
     * Process the element source content to produce a result.
     *
     * @abstract Implement this to define behavior for a MODX content element.
     *
     * @param array|string $properties A set of configuration properties for the
     *                                 element.
     * @param string       $content    Optional content to use in place of any persistent
     *                                 content associated with the element.
     *
     * @return mixed The result of processing.
     */
    public function process($properties = null, $content = null)
    {
        $this->xpdo->getParser();
        $this->xpdo->parser->setProcessingElement(true);
        $this->getProperties($properties);
        $this->getTag();
        if ($this->xpdo->getDebug() === true) {
            $this->xpdo->log(
                xPDO::LOG_LEVEL_DEBUG,
                "Processing Element: " . $this->get('name') . ($this->_tag ? "\nTag: {$this->_tag}" : "\n") .
                    "\nProperties: " . print_r($this->_properties, true)
            );
        }
        if ($this->isCacheable() && isset($this->xpdo->elementCache[$this->_tag])) {
            $this->_output = $this->xpdo->elementCache[$this->_tag];
            $this->_processed = true;
        } else {
            $this->filterInput();
            $this->getContent(is_string($content) ? ['content' => $content] : []);
        }

        return $this->_result;
    }

    /**
     * Cache the current output of this element instance by tag signature.
     */
    public function cache()
    {
        if ($this->isCacheable()) {
            $this->xpdo->elementCache[$this->_tag] = $this->_output;
        }
    }

    /**
     * Get an input filter instance configured for this Element.
     *
     * @return modInputFilter|null An input filter instance (or null if one cannot be loaded).
     */
    public function & getInputFilter()
    {
        if (!isset($this->_filters['input']) || !($this->_filters['input'] instanceof modInputFilter)) {
            if (!$inputFilterClass = $this->get('input_filter')) {
                $inputFilterClass = $this->xpdo->getOption('input_filter', null, modInputFilter::class);
            }
            if ($filterClass = $this->xpdo->loadClass($inputFilterClass, '', false, true)) {
                if ($filter = new $filterClass($this->xpdo)) {
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
        if (!isset($this->_filters['output']) || !($this->_filters['output'] instanceof modOutputFilter)) {
            if (!$outputFilterClass = $this->get('output_filter')) {
                $outputFilterClass = $this->xpdo->getOption('output_filter', null, modOutputFilter::class);
            }
            if ($filterClass = $this->xpdo->loadClass($outputFilterClass, '', false, true)) {
                if ($filter = new $filterClass($this->xpdo)) {
                    $this->_filters['output'] = $filter;
                }
            }
        }

        return $this->_filters['output'];
    }

    /**
     * Apply an input filter to an element.
     *
     * This is called by default in {@link modElement::process()} after the
     * element properties have been parsed.
     */
    public function filterInput()
    {
        $filter = $this->getInputFilter();
        if ($filter instanceof modInputFilter) {
            $filter->filter($this);
        }
    }

    /**
     * Apply an output filter to an element.
     *
     * Call this method in your {modElement::process()} implementation when it
     * is appropriate, typically once all processing has been completed, but
     * before any caching takes place.
     */
    public function filterOutput()
    {
        $filter = $this->getOutputFilter();
        if ($filter instanceof modOutputFilter) {
            $filter->filter($this);
        }
    }

    /**
     * Loads the access control policies applicable to this element.
     *
     * {@inheritdoc}
     */
    public function findPolicy($context = '')
    {
        $policy = [];
        $enabled = true;
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if ($context === $this->xpdo->context->get('key')) {
            $enabled = (bool)$this->xpdo->getOption('access_category_enabled', null, true);
        } elseif ($this->xpdo->getContext($context)) {
            $enabled = (bool)$this->xpdo->contexts[$context]->getOption('access_category_enabled', true);
        }
        if ($enabled) {
            if (empty($this->_policies) || !isset($this->_policies[$context])) {
                $accessTable = $this->xpdo->getTableName(modAccessCategory::class);
                $policyTable = $this->xpdo->getTableName(modAccessPolicy::class);
                $categoryClosureTable = $this->xpdo->getTableName(modCategoryClosure::class);
                $sql = "SELECT Acl.target, Acl.principal, Acl.authority, Acl.policy, Policy.data FROM {$accessTable} Acl " .
                    "LEFT JOIN {$policyTable} Policy ON Policy.id = Acl.policy " .
                    "JOIN {$categoryClosureTable} CategoryClosure ON CategoryClosure.descendant = :category " .
                    "AND Acl.principal_class = {$this->xpdo->quote(modUserGroup::class)} " .
                    "AND CategoryClosure.ancestor = Acl.target " .
                    "AND (Acl.context_key = :context OR Acl.context_key IS NULL OR Acl.context_key = '') " .
                    "ORDER BY CategoryClosure.depth DESC, target, principal, authority ASC";
                $bindings = [
                    ':category' => $this->get('category'),
                    ':context' => $context,
                ];
                $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
                if ($query->stmt && $query->stmt->execute()) {
                    while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                        $policy[modAccessCategory::class][$row['target']][] = [
                            'principal' => $row['principal'],
                            'authority' => $row['authority'],
                            'policy' => $row['data'] ? $this->xpdo->fromJSON($row['data'], true) : [],
                        ];
                    }
                }
                $this->_policies[$context] = $policy;
            } else {
                $policy = $this->_policies[$context];
            }
        }

        return $policy;
    }

    /**
     * Gets the raw, unprocessed source content for this element.
     *
     * @param array $options An array of options implementations can use to
     *                       accept language, revision identifiers, or other information to alter the
     *                       behavior of the method.
     *
     * @return string The raw source content for the element.
     */
    public function getContent(array $options = [])
    {
        if (!is_string($this->_content) || $this->_content === '') {
            if (isset($options['content'])) {
                $this->_content = $options['content'];
            } elseif ($this->isStatic()) {
                $this->_content = $this->getFileContent($options);
                if ($this->_content !== $this->_fields['content']) {
                    $this->setContent($this->_content);
                    if (!$this->isNew()) {
                        $this->save();
                    }
                }
            } else {
                $this->_content = $this->get('content');
            }
        }

        return $this->_content;
    }

    /**
     * Set the raw source content for this element.
     *
     * @param mixed $content The source content; implementations can decide if
     *                       it can only be a string, or some other source from which to retrieve it.
     * @param array $options An array of options implementations can use to
     *                       accept language, revision identifiers, or other information to alter the
     *                       behavior of the method.
     *
     * @return bool True indicates the content was set.
     */
    public function setContent($content, array $options = [])
    {
        return $this->set('content', $content);
    }

    /**
     * Get the absolute path to the static source file for this instance.
     *
     * @param array $options An array of options.
     *
     * @return string|bool The absolute path to the static source file or false if not static.
     */
    public function getSourceFile(array $options = [])
    {
        if (!$this->isStatic()) {
            return false;
        }

        if (empty($this->_sourceFile) || $this->getOption('recalculate_source_file', $options, $this->staticSourceChanged())) {
            $filename = $this->get('static_file');
            if (!empty($filename)) {
                $array = [];
                if ($this->xpdo->getParser() && $this->xpdo->parser->collectElementTags($filename, $array)) {
                    $this->xpdo->parser->processElementTags('', $filename);
                }
            }

            if ($this->get('source') > 0) {
                /** @var modMediaSource $source */
                $source = $this->getOne('Source');
                if ($source && $source->get('is_stream')) {
                    $source->initialize();
                    $filename = $source->getBasePath() . $filename;
                }
            }

            if (!file_exists($filename) && $this->get('source') < 1) {
                $this->getSourcePath($options);
                $this->_sourceFile = $this->_sourcePath . $filename;
            } else {
                $this->_sourceFile = $filename;
            }
        }

        return $this->_sourceFile;
    }


    /**
     * Get the absolute path location the source file is located relative to.
     *
     * @param array $options An array of options.
     *
     * @return string The path to file.
     */
    public function getSourcePath(array $options = [])
    {
        $array = [];
        $this->_sourcePath = $this->xpdo->getOption(
            'element_static_path',
            $options,
            $this->xpdo->getOption('components_path', $options, MODX_CORE_PATH . 'components/')
        );
        if ($this->xpdo->getParser() && $this->xpdo->parser->collectElementTags($this->_sourcePath, $array)) {
            $this->xpdo->parser->processElementTags('', $this->_sourcePath);
        }

        return str_replace(MODX_CORE_PATH, '', $this->_sourcePath);
    }


    /**
     * Get the content stored in an external file for this instance.
     *
     * @param array $options An array of options.
     *
     * @return bool|string The content or false if the content could not be retrieved.
     */
    public function getFileContent(array $options = [])
    {
        $content = "";
        if ($this->isStatic()) {
            $sourceFile = $this->getSourceFile($options);
            if ($sourceFile && file_exists($sourceFile)) {
                $content = file_get_contents($sourceFile);
            }
        }

        return $content;
    }


    /**
     * Set external file content from this instance.
     *
     * @param string $content The content to set.
     * @param array  $options An array of options.
     *
     * @return bool
     */
    public function setFileContent($content, array $options = [])
    {
        $set = false;
        if ($this->isStatic()) {
            $sourceFile = $this->getSourceFile($options);
            if ($sourceFile) {
                $set = $this->xpdo->cacheManager->writeFile($sourceFile, $content);
            }
        }

        return $set;
    }


    /**
     * Get the properties for this element instance for processing.
     *
     * @param array|string $properties An array or string of properties to
     *                                 apply.
     *
     * @return array A simple array of properties ready to use for processing.
     */
    public function getProperties($properties = null)
    {
        $this->xpdo->getParser();
        $this->_properties = $this->xpdo->parser->parseProperties($this->get('properties'));
        $set = $this->getPropertySet();
        if (!empty($set)) {
            $this->_properties = array_merge($this->_properties, $set);
        }
        if ($this->get('property_preprocess')) {
            foreach ($this->_properties as $pKey => $pValue) {
                if ($this->xpdo->parser->processElementTags('', $pValue, $this->xpdo->parser->isProcessingUncacheable())) {
                    $this->_properties[$pKey] = $pValue;
                }
            }
        }
        if (!empty($properties)) {
            $this->_properties = array_merge($this->_properties, $this->xpdo->parser->parseProperties($properties));
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
                $psObj = $this->xpdo->getObjectGraph(modPropertySet::class, '{"Elements":{}}', [
                    'Elements.element' => $this->id,
                    'Elements.element_class' => $this->_class,
                    'modPropertySet.name' => $psName,
                ]);
                if ($psObj) {
                    $propertySet = $this->xpdo->parser->parseProperties($psObj->get('properties'));
                }
            }
        }
        if (!empty($setName)) {
            $propertySetObj = $this->xpdo->getObjectGraph(modPropertySet::class, '{"Elements":{}}', [
                'Elements.element' => $this->id,
                'Elements.element_class' => $this->_class,
                'modPropertySet.name' => $setName,
            ]);
            if ($propertySetObj) {
                if (is_array($propertySet)) {
                    $propertySet = array_merge($propertySet, $this->xpdo->parser->parseProperties($propertySetObj->get('properties')));
                } else {
                    $propertySet = $this->xpdo->parser->parseProperties($propertySetObj->get('properties'));
                }
            }
        }

        return $propertySet;
    }

    /**
     * Set default properties for this element instance.
     *
     * @access public
     *
     * @param array|string $properties A property array or property string.
     * @param bool      $merge      Indicates if properties should be merged with
     *                                 existing ones.
     *
     * @return bool true if the properties are set.
     */
    public function setProperties($properties, $merge = false)
    {
        $set = false;
        $propertiesArray = [];
        if (is_string($properties)) {
            $properties = $this->xpdo->parser->parsePropertyString($properties);
        }
        if (is_array($properties)) {
            foreach ($properties as $propKey => $property) {
                if (is_array($property) && isset($property[5])) {
                    $key = $property[0];
                    $propertyArray = [
                        'name' => $property[0],
                        'desc' => $property[1],
                        'type' => $property[2],
                        'options' => $property[3],
                        'value' => $property[4],
                        'lexicon' => !empty($property[5]) ? $property[5] : null,
                        'area' => !empty($property[6]) ? $property[6] : '',
                    ];
                } elseif (is_array($property) && isset($property['value'])) {
                    $key = $property['name'];
                    $propertyArray = [
                        'name' => $property['name'],
                        'desc' => isset($property['description']) ? $property['description'] : (isset($property['desc']) ? $property['desc'] : ''),
                        'type' => isset($property['xtype']) ? $property['xtype'] : (isset($property['type']) ? $property['type'] : 'textfield'),
                        'options' => isset($property['options']) ? $property['options'] : [],
                        'value' => $property['value'],
                        'lexicon' => !empty($property['lexicon']) ? $property['lexicon'] : null,
                        'area' => !empty($property['area']) ? $property['area'] : '',
                    ];
                } else {
                    $key = $propKey;
                    $propertyArray = [
                        'name' => $propKey,
                        'desc' => '',
                        'type' => 'textfield',
                        'options' => [],
                        'value' => $property,
                        'lexicon' => null,
                        'area' => '',
                    ];
                }

                if (!empty($propertyArray['options'])) {
                    foreach ($propertyArray['options'] as $optionKey => &$option) {
                        if (empty($option['text']) && !empty($option['name'])) {
                            $option['text'] = $option['name'];
                        }
                        unset($option['menu'], $option['name']);
                    }
                }

                if ($propertyArray['type'] == 'combo-boolean' && is_numeric($propertyArray['value'])) {
                    $propertyArray['value'] = (bool)$propertyArray['value'];
                }

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
     * Add a property set to this element, making it available for use.
     *
     * @access public
     *
     * @param string|modPropertySet $propertySet A modPropertySet object or the
     *                                           name of a modPropertySet object to create a relationship with.
     *
     * @return bool True if a relationship was created or already exists.
     */
    public function addPropertySet($propertySet)
    {
        $added = false;
        if (!empty($propertySet)) {
            if (is_string($propertySet)) {
                $propertySet = $this->xpdo->getObject(modPropertySet::class, ['name' => $propertySet]);
            }
            if (is_object($propertySet) && $propertySet instanceof modPropertySet) {
                if (
                    !$this->isNew()
                    && !$propertySet->isNew()
                    && $this->xpdo->getCount(modElementPropertySet::class, [
                        'element' => $this->get('id'),
                        'element_class' => $this->_class,
                        'property_set' => $propertySet->get('id'),
                    ])
                ) {
                    $added = true;
                } else {
                    if ($propertySet->isNew()) {
                        $propertySet->save();
                    }
                    /** @var modElementPropertySet $link */
                    $link = $this->xpdo->newObject(modElementPropertySet::class);
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
     *
     * @param string|modPropertySet $propertySet A modPropertySet object or the
     *                                           name of a modPropertySet object to dissociate from.
     *
     * @return bool True if a relationship was destroyed.
     */
    public function removePropertySet($propertySet)
    {
        $removed = false;
        if (!empty($propertySet)) {
            if (is_string($propertySet)) {
                $propertySet = $this->xpdo->getObject(modPropertySet::class, ['name' => $propertySet]);
            }
            if ($propertySet instanceof modPropertySet) {
                $removed = $this->xpdo->removeObject(modElementPropertySet::class, [
                    'element' => $this->get('id'),
                    'element_class' => $this->_class,
                    'property_set' => $propertySet->get('id'),
                ]);
            }
        }

        return $removed;
    }

    /**
     * Indicates if the element is cacheable.
     *
     * @access public
     * @return bool True if the element can be stored to or retrieved from the element cache.
     */
    public function isCacheable()
    {
        return $this->_cacheable;
    }

    /**
     * Sets the runtime cacheability of the element.
     *
     * @access public
     *
     * @param bool $cacheable Indicates the value to set for cacheability of
     *                           this element.
     */
    public function setCacheable($cacheable = true)
    {
        $this->_cacheable = (bool)$cacheable;
    }

    /**
     * Get the Source for this Element
     *
     * @param string  $contextKey
     * @param bool $fallbackToDefault
     *
     * @return modMediaSource|null
     */
    public function getSource($contextKey = '', $fallbackToDefault = true)
    {
        if (empty($contextKey)) {
            $contextKey = $this->xpdo->context->get('key');
        }

        $source = $this->_source;

        if (empty($source)) {
            $c = $this->xpdo->newQuery(modMediaSource::class);
            $c->innerJoin(modMediaSourceElement::class, 'SourceElement');
            $c->where([
                'SourceElement.object' => $this->get('id'),
                'SourceElement.object_class' => $this->_class,
                'SourceElement.context_key' => $contextKey,
            ]);
            $source = $this->xpdo->getObject(modMediaSource::class, $c);
            if (!$source && $fallbackToDefault) {
                $source = modMediaSource::getDefaultSource($this->xpdo, $this->get('source'));
            }
            if ($source) {
                $this->setSource($source);
            }
        }

        return $source;
    }

    /**
     * Setter method for the source class var.
     *
     * @param modMediaSourceInterface $source The source to use for this element.
     */
    public function setSource(modMediaSourceInterface $source)
    {
        $source->initialize();
        $this->_source = $source;
    }

    /**
     * Get the stored sourceCache for a context
     *
     * @param string $contextKey
     * @param array  $options
     *
     * @return array
     */
    public function getSourceCache($contextKey = '', array $options = [])
    {
        /** @var modCacheManager $cacheManager */
        $cacheManager = $this->xpdo->getCacheManager();
        if (!$cacheManager instanceof modCacheManager) {
            return [];
        }

        if (empty($contextKey)) {
            $contextKey = $this->xpdo->context->get('key');
        }

        return $cacheManager->getElementMediaSourceCache($this, $contextKey, $options);
    }

    /**
     * Indicates if the instance has content in an external file.
     *
     * @return bool True if the instance has content stored in an external file.
     */
    public function isStatic()
    {
        return $this->get('static');
    }

    /**
     * Indicates if the content has changed and the Element has a mutable static source.
     *
     * @return bool
     */
    public function staticContentChanged()
    {
        return $this->isStatic() && $this->isDirty('content');
    }

    /**
     * Check if directories are empty after moving a static element and remove empty directories.
     *
     * @param $dirname
     */
    public function cleanupStaticFileDirectories($dirname)
    {
        $contents = array_diff(scandir($dirname), ['..', '.', '.DS_Store']);

        @unlink($dirname . '/.DS_Store');
        if (count($contents) === 0) {
            if (is_dir($dirname)) {
                if (rmdir($dirname)) {
                    /* Check if parent directory is also empty. */
                    $this->cleanupStaticFileDirectories(dirname($dirname));
                }
            }
        }
    }

    /**
     * Returns static file path if the file path or source has changed.
     */
    public function getOldStaticFilePath()
    {
        $oldFilePath = '';
        $sourceId = 0;

        $result = $this->xpdo->getObject($this->_class, ['id' => $this->_fields['id']]);
        if ($result) {
            $staticFilePath = $result->get('static_file');
            $sourceId = $result->get('source');
            if ($staticFilePath !== $this->_fields['static_file'] || $sourceId !== $this->_fields['source']) {
                $oldFilePath = $staticFilePath;
            }
        }

        if (!empty($oldFilePath)) {
            if ($sourceId > 0) {
                /** @var modMediaSource $source */
                $source = $this->xpdo->getObject(modFileMediaSource::class, ['id' => $sourceId]);
                if ($source && $source->get('is_stream')) {
                    $source->initialize();
                    $oldFilePath = $source->getBasePath() . $oldFilePath;
                }
            }

            if (!file_exists($oldFilePath) && $this->get('source') < 1) {
                $this->getSourcePath();
                $oldFilePath = $this->_sourcePath . $oldFilePath;
            }
        }

        return $oldFilePath;
    }

    /**
     * Indicates if the static source has changed.
     *
     * @return bool
     */
    public function staticSourceChanged()
    {
        return $this->isStatic() && ($this->isDirty('static') || $this->isDirty('static_file') || $this->isDirty('source'));
    }

    /**
     * Return if the static source is mutable.
     *
     * @return bool True if the source file is mutable.
     */
    public function isStaticSourceMutable()
    {
        $isMutable = false;
        $sourceFile = $this->getSourceFile();
        if ($sourceFile) {
            if (file_exists($sourceFile)) {
                $isMutable = is_writable($sourceFile) && !is_dir($sourceFile);
            } else {
                $sourceDir = dirname($sourceFile);
                $i = 100;
                while (!empty($sourceDir)) {
                    if (file_exists($sourceDir) && is_dir($sourceDir)) {
                        $isMutable = is_writable($sourceDir);
                        if ($isMutable) {
                            break;
                        }
                    }
                    if ($sourceDir != '/') {
                        $sourceDir = dirname($sourceDir);
                    } else {
                        break;
                    }
                    $i--;
                    if ($i < 0) {
                        break;
                    }
                }
            }
        }

        return $isMutable;
    }

    /**
     * Ensure the static source cannot browse the protected configuration directory
     *
     * @return bool True if is a valid source path
     */
    public function isStaticSourceValidPath()
    {
        $isValid = true;
        $sourceFile = $this->getSourceFile();
        if ($sourceFile) {
            $sourceDirectory = rtrim(dirname($sourceFile), '/');
            $configDirectory = rtrim($this->xpdo->getOption('core_path', null, MODX_CORE_PATH) . 'config/', '/');
            if ($sourceDirectory == $configDirectory) {
                $isValid = false;
            }
        }

        return $isValid;
    }

    /**
     * Get the absolute path to the preview file for this instance.
     *
     * @return string
     */
    public function getPreviewUrl()
    {
        if (!empty($this->get('preview_file'))) {
            $previewfile = $this->get('preview_file');

            if ($this->get('source') > 0) {
                /** @var modMediaSource $source */
                $source = $this->getOne('Source');

                if ($source && $source->get('is_stream')) {
                    $source->initialize();

                    if (file_exists($source->getBasePath() . $previewfile)) {
                        return $source->getBaseUrl() . $previewfile;
                    }
                }
            } elseif (file_exists(MODX_BASE_PATH . $previewfile)) {
                return MODX_BASE_URL . $previewfile;
            }

            if (file_exists($previewfile)) {
                return $previewfile;
            }
        }

        return '';
    }
}
