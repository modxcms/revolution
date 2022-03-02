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
 * @property integer            $source
 * @property boolean            $property_preprocess
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
     * The boolean result of the element.
     *
     * This is typically only applicable to elements that use PHP source content.
     *
     * @var boolean
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
     * @var boolean If the element is cacheable or not.
     */
    public $_cacheable = true;
    /**
     * @var boolean Indicates if the element was processed already.
     */
    public $_processed = false;
    /**
     * @var array Optional filters that can be used during processing.
     */
    public $_filters = ['input' => null, 'output' => null];

    /**
     * @var string Path to source file location when modElement->isStatic() === true.
     */
    protected $_sourcePath = '';
    /**
     * @var string Source file name when modElement->isStatic() === true.
     */
    protected $_sourceFile = '';
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

    /*
        NOTE: The 2 properties that follow also get set on the modMediaSource
        instance via relayStaticPropertiesToMediaSource() below
    */

    /**
     * @property bool $isStaticElementFile Provides context to the file handling class(es)
     */
    public $isStaticElementFile = false;

    /**
     * @property bool $ignoreMediaSource Indicates that a static file's source is set to "None"
     */
    public $ignoreMediaSource = false;

    /**
     * @property bool $staticPathIsAbsolute When true, indicates that the
     * static file does no belong to a media source (it is set to "None")
     * and its path begins with the DIRECTORY_SEPARATOR
     */
    public $staticPathIsAbsolute = false;

    /**
     * @property int|null $staticElementMediaSourceId The stored source id for later use
     */
    public $staticElementMediaSourceId = null;

    /**
     * @property string $staticFileAbsolutePath The full path to the specified static file,
     * calculated based on a media source or as given (when media source is set to "None" and
     * a valid absolute path to the file is provided)
     */
    public $staticFileAbsolutePath = '';

    /**
     * @property bool $staticIsWritable The result of validateStaticFile(),
     * which is called from the Element Create, Update, and Duplicate processors
     */
    public $staticIsWritable = false;

    /**
     * Provides custom handling for retrieving the properties field of an Element.
     *
     * {@inheritdoc}
     */
    public function get($k, $format = null, $formatTemplate = null)
    {
        $value = parent::get($k, $format, $formatTemplate);
        if ($k === 'properties' && $this->xpdo instanceof modX && $this->xpdo->getParser() && empty($value)) {
            $value = !empty($this->properties) && is_string($this->properties)
                ? $this->xpdo->parser->parsePropertyString($this->properties)
                : null;
        }
        /* automatically translate lexicon descriptions */
        if (
            $k == 'properties' && !empty($value) && is_array($value)
            && is_object($this->xpdo) && $this->xpdo instanceof modX && $this->xpdo->lexicon
        ) {
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
        /*
            QUESTION:
            [?] Exactly what gets used here when in setup mode?
        */
        if (!$this->isStatic()) {
            return parent::save($cacheFlag);
        }

        $inSetupMode = $this->getOption(xPDO::OPT_SETUP);
        $elementSaved = false;
        $fileSaved = false;
        // This returns a fully-expanded path if present
        $filePathFromDatabase = !$this->isNew() ? $this->getOldStaticFilePath() : false ;
        $inAutomationMode = $this->isStaticFilesAutomated();

        if (!$inSetupMode) {
            // Get the content present in the Element's code field
            $elementContent = $this->get('content');
            $staticSourceChanged = $this->staticSourceChanged();

            if ($staticSourceChanged) {
                $staticFileContent = $this->getFileContent();
                if ($staticFileContent !== $elementContent) {
                    if (empty($staticFileContent)) {
                        $this->setDirty('content');
                    } else {
                        $this->setContent($staticFileContent);
                    }
                }
                unset($staticFileContent);
            }

            $staticContentChanged = $this->staticContentChanged();

            if ($staticContentChanged) {
                if ($this->staticIsWritable) {
                    $fileSaved = $this->setFileContent($this->get('content'));
                } else {
                    $this->setContent($this->getFileContent());
                }
            }
        }

        if ($fileSaved || (!$staticSourceChanged && !$staticContentChanged)) {
            $elementSaved = parent::save($cacheFlag);
        }

        /*
            Remove old static file in scenarios where not removing would result in a duplicate file
            being left behind on the filesystem.
        */
        if ($filePathFromDatabase && $fileSaved && $elementSaved) {
            $fileNamesMatch = basename($this->staticFileAbsolutePath) == basename($filePathFromDatabase);

            if (($staticSourceChanged && $fileNamesMatch) || ($inAutomationMode && !$this->staticPathIsAbsolute)) {
                /*
                    Remove previous file when path is changed, but leave directories (even if empty) when
                    not in automation mode. Consider system setting to auto-remove empty directories to allow
                    this to be a global behavior.
                */
                if (@unlink($filePathFromDatabase)) {
                    if ($inAutomationMode) {
                        $pathinfo = pathinfo($filePathFromDatabase);
                        $this->cleanupStaticFileDirectories($pathinfo['dirname']);
                    }
                }
            }
        }
        return $elementSaved;
    }

    /**
     * Determine if static files should be automated for current element class.
     *
     * @return bool
     */
    public function isStaticFilesAutomated()
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
                            array_walk_recursive($value, function (&$item, $key) {
                                if ($item instanceof \xPDOObject) {
                                    $item = $item->toArray('', false, true);
                                }
                            });
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
            $tag .= $this->get('name');
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
                "Processing Element: " . $this->get('name') . ($this->_tag ? "\nTag: {$this->_tag}" : "\n") . "\nProperties: " . print_r(
                    $this->_properties,
                    true
                )
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
        if ($filter !== null && $filter instanceof modInputFilter) {
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
        if ($filter !== null && $filter instanceof modOutputFilter) {
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
                    $this->syncDbContentToFile();
                }
            } else {
                $this->_content = $this->get('content');
            }
        }

        return $this->_content;
    }

    /**
     * Saves content from a changed static file to the database.
     */
    protected function syncDbContentToFile()
    {
        $this->setContent($this->_content);
        if (!$this->isNew()) {
            /*
                Skip this class' save method, as the only purpose of this method and
                its parent method is to ensure that the static content field picks up
                and saves any changes present in the static file itself.

                QUESTION: Is the cache flag relevant here? I don't believe it is.
            */
            parent::save();
        }
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
     * @return boolean True indicates the content was set.
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
     * @return string|boolean The absolute path to the static source file (if streamable), a relative path (if needs to be loaded through media source) or false if not static/available.
     */
    public function getSourceFile(array $options = [])
    {
        // Only static files have a source file
        if (!$this->isStatic()) {
            return false;
        }

        // Cache the path calculation - unless provided otherwise
        $recalculate = $this->getOption('recalculate_source_file', $options, $this->staticSourceChanged());

        if (!empty($this->_sourceFile) && !$recalculate) {
            return $this->_sourceFile;
        }

        $result = false;

        if (empty($this->_source)) {
            $this->getSource();
        }

        $filename = $this->getStaticFileName();

        if ($this->ignoreMediaSource) {
            if ($this->staticPathIsAbsolute) {
                $result = $filename;
            } else {
                // Create absolute path from relative path in site root by default
                $result = $this->_source->getBasePath() . $filename;
            }
        } else {
            // If a media source is assigned, fetch it
            if ($this->get('source') > 0) {
                if ($this->_source) {
                    if ($this->_source->get('is_stream')) {
                        // Return full file path for streaming sources
                        $result = $this->_source->getBasePath() . $filename;
                    } elseif ($this->_source->getFilesystem()->fileExists($filename)) {
                        // If we can find a file relative to the source, return just the relative path
                        $result = $filename;
                    }
                } else {
                    $result = false;
                }
            } elseif (is_readable($filename)) {
                // If the file is a fully qualified path we can access, use it
                $result = $filename;
            } elseif (($sourcePath = $this->getSourcePath()) && is_readable($sourcePath . $filename)) {
                // If the file is located and accessible in the sources (components) path, use that
                $result = $sourcePath . $filename;
            }
        }
        $this->_sourceFile = $result;

        return $result;
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
            $this->xpdo->getOption('components_path', $options, 'components/')
        );
        if ($this->xpdo->getParser() && $this->xpdo->parser->collectElementTags($this->_sourcePath, $array)) {
            $this->xpdo->parser->processElementTags('', $this->_sourcePath);
        }

        return $this->_sourcePath;
    }

    /**
     * Get the content stored in an external file for this instance.
     *
     * @param array $options An array of options.
     *
     * @return bool|string The content or false if the content could not be retrieved.
     */
    // Tmp Note: Method also used by modScript, which extends this class
    public function getFileContent(array $options = []): string
    {
        $content = '';
        $file = $this->get('static_file');

        if ($this->isStatic() && !empty($file)) {
            $sourceId = (int)$this->get('source');
            if ($sourceId === 0) {
                $this->ignoreMediaSource = true;
                if (strpos($file, '/') === 0) {
                    $this->staticPathIsAbsolute = true;
                }
            } else {
                if (!$this->_source) {
                    $this->getSource();
                }
            }

            $sourceFile = $this->getSourceFile($options);

            if (!$this->ignoreMediaSource) {
                if ($this->_source->get('is_stream')) {
                    $sourceBase = $this->_source->getBasePath();
                    $sourceFile = str_replace($sourceBase, '', $sourceFile);
                }
                if ($file = $this->_source->getObjectContents($sourceFile)) {
                    $content = $file['content'];
                }
            } else {
                $this->staticFileAbsolutePath = $sourceFile;
                $content = is_readable($sourceFile) ? file_get_contents($sourceFile) : '' ;
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
    public function setFileContent($content, array $options = []): bool
    {
        $set = false;
        if ($this->isStatic()) {
            if (!$this->_source) {
                $this->getSource();
            }
            $sourceFile = $this->getStaticFileName();

            // When in update mode, this will ensure the file is copied to its new location if its source gets changed
            $recreateStaticFile = $this->staticSourceChanged() && !empty($this->staticFileAbsolutePath) && !file_exists($this->staticFileAbsolutePath);

            if (!$this->ignoreMediaSource) {
                if ($this->isNew() || $recreateStaticFile) {
                    if (!$recreateStaticFile && file_exists($this->staticFileAbsolutePath)) {
                        // Returns true to skip attempting file creation when the file is already in place
                        return true;
                    }
                    $path = explode(DIRECTORY_SEPARATOR, trim($sourceFile, DIRECTORY_SEPARATOR));
                    $file = array_pop($path);
                    $set = (bool)$this->_source->createObject(implode(DIRECTORY_SEPARATOR, $path), $file, $content);
                } else {
                    $set = (bool)$this->_source->updateObject($sourceFile, $content);
                }
            } else {
                $targetDirectory = dirname($this->staticFileAbsolutePath);
                $directoryReady = true;

                if (!is_dir($targetDirectory)) {
                    if (!@mkdir($targetDirectory, 0755, true)) {
                        $mkdirError = error_get_last();
                    }
                    clearstatcache(true, $targetDirectory);
                    if (!is_dir($targetDirectory)) {
                        $directoryReady = false;
                        $errorMessage = isset($mkdirError['message']) ? $mkdirError['message'] : '';
                        $this->xpdo->log(modX::LOG_LEVEL_ERROR, $errorMessage);
                    }
                }
                if ($directoryReady) {
                    $set = file_put_contents($this->staticFileAbsolutePath, $content, LOCK_EX) !== false;
                }
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
                if (
                    $this->xpdo->parser->processElementTags(
                        '',
                        $pValue,
                        $this->xpdo->parser->isProcessingUncacheable()
                    )
                ) {
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
                    $propertySet = array_merge(
                        $propertySet,
                        $this->xpdo->parser->parseProperties($propertySetObj->get('properties'))
                    );
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
     * @param boolean      $merge      Indicates if properties should be merged with
     *                                 existing ones.
     *
     * @return boolean true if the properties are set.
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
     * @return boolean True if a relationship was created or already exists.
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
     * @return boolean True if a relationship was destroyed.
     */
    public function removePropertySet($propertySet)
    {
        $removed = false;
        if (!empty($propertySet)) {
            if (is_string($propertySet)) {
                $propertySet = $this->xpdo->getObject(modPropertySet::class, ['name' => $propertySet]);
            }
            if (is_object($propertySet) && $propertySet instanceof modPropertySet) {
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
     * @access public
     *
     * @param boolean $cacheable Indicates the value to set for cacheability of
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
     * @param boolean $fallbackToDefault
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
            $elementId = $this->get('id');
            $sourceId = $this->staticElementMediaSourceId !== null
                ? $this->staticElementMediaSourceId
                : (int)$this->get('source')
                ;
            /*
                NOTE/QUESTION: The query below would seem to only grab a source related to a TV, as
                the only object class seen in the media_sources_elements table entries is
                MODX\Revolution\modTemplateVar. Is that intentional?

                This also seems to not apply to the mgr context. Is that right?
            */
            if ($sourceId > 0) {
                $c = $this->xpdo->newQuery(modMediaSource::class);
                $c->innerJoin(modMediaSourceElement::class, 'SourceElement');
                $c->where([
                    'SourceElement.object' => $elementId,
                    'SourceElement.object_class' => $this->_class,
                    'SourceElement.context_key' => $contextKey,
                ]);
                $source = $this->xpdo->getObject(modMediaSource::class, $c);
            }
            if (!$source && $fallbackToDefault) {
                $source = modMediaSource::getDefaultSource($this->xpdo, $sourceId);
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
        if (!$cacheManager || !($cacheManager instanceof modCacheManager)) {
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
     * @return boolean True if the instance has content stored in an external file.
     */
    public function isStatic()
    {
        return $this->get('static');
    }

    /**
     * Indicates if the content has changed and the Element has a mutable static source.
     *
     * @return boolean
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
        }

        return $oldFilePath;
    }

    /**
     * Indicates if the static source has changed.
     *
     * @return boolean
     */
    public function staticSourceChanged()
    {
        return $this->isStatic() && ($this->isDirty('static') || $this->isDirty('static_file') || $this->isDirty('source'));
    }

    /**
     * DEPRECATED - Ensure the static source cannot browse the protected configuration directory
     *
     * @return boolean True if is a valid source path
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
            } else {
                // Return "as is" if not assigned to a media source
                if (file_exists(MODX_BASE_PATH . $previewfile)) {
                    return MODX_BASE_URL . $previewfile;
                }
            }

            if (file_exists($previewfile)) {
                return $previewfile;
            }
        }

        return '';
    }

    private function getStaticFileName()
    {
        $filename = $this->get('static_file');
        if (!empty($filename)) {
            $array = [];
            if ($this->xpdo->getParser() && $this->xpdo->parser->collectElementTags($filename, $array)) {
                $this->xpdo->parser->processElementTags('', $filename);
            }
        }
        return $filename;
    }

    /* -- Proposed new methods/rewrites -- */

    /**
     * @property object $processor The processor instance currently working on this Element
     */
    public function setupElement($processor)
    {
        /*
            There is at least one other Element type (modPropertySet) where static files
            do not apply, so here we determine whether static processing will be needed
            based on the Element being created.
        */
        $hasStaticContentOption = in_array(
            $this->_class,
            [modChunk::class, modPlugin::class, modSnippet::class, modTemplate::class, modTemplateVar::class]
        );
        $isStatic = $processor->getProperty('static', 0);
        $isStatic = intval($isStatic) === 1 || $isStatic == 'true' || $isStatic === true ? true : false ;

        if ($hasStaticContentOption && $isStatic) {
            $file = $processor->getProperty('static_file');

            if (!empty($file)) {
                $processor->hasStaticFile = true;
                $mediaSourceId = (int)$processor->getProperty('source');
                // When file media source is set to "None"
                if ($mediaSourceId === 0) {
                    $this->ignoreMediaSource = true;
                    if (strpos($file, '/') === 0) {
                        $this->staticPathIsAbsolute = true;
                    }
                }
                // When there is an assigned media source
                if ($mediaSourceId > 0) {
                    $this->ignoreMediaSource = false;
                    $processor->setProperty('static_file', ltrim($file, DIRECTORY_SEPARATOR));
                }

                $this->staticElementMediaSourceId = $mediaSourceId;
                $this->isStaticElementFile = true;

                if ($this->getSource()) {
                    // Stop if error fetching media source
                    if ($this->_source->hasErrors()) {
                        $processor->addFieldError('static_file', reset($this->_source->getErrors()));
                        return false;
                    }
                }
                $this->relayStaticPropertiesToMediaSource([
                    'isStaticElementFile',
                    'ignoreMediaSource'
                ]);
            }
        }
        return true;
    }

    /**
     * Copies the needed properties from this class to the modMediaSource instance
     *
     * @property array $config A set of Element property names to relay
     */
    public function relayStaticPropertiesToMediaSource(array $config)
    {
        foreach ($config as $property) {
            $this->_source->$property = $this->$property;
        }
    }

    /**
     * Get the full server path to a file, taking into account whether the file
     * is assigned to a media source and, if so, whether that source is streaming
     *
     * @return string Server path to file
     */
    public function getStaticFileAbsolutePath(): string
    {
        $path = '';

        if ($this->_source || $this->getSource()) {
            if (!$this->ignoreMediaSource && !$this->_source->get('is_stream')) {
                $path = $this->_source->getBasePath() . $this->getStaticFileName();
            } else {
                $path = $this->getSourceFile();
            }
        }
        return $path;
    }

    /**
     * Checks the proposed $path against a pre-defined array of protected directories.
     * Supercedes isStaticSourceValidPath()
     */
    public function pathIsProtected($path): bool
    {
        $protectedPaths = [
            rtrim($this->xpdo->getOption('core_path', null, MODX_CORE_PATH) . 'config' . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR)
        ];
        if ($path) {
            $sourceDirectory = rtrim(dirname($path), DIRECTORY_SEPARATOR);
            if (in_array($sourceDirectory, $protectedPaths)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determines whether the given file exists or can be created, as well
     * as whether its extension is valid based on its destination
     *
     * @property object $processor The processor instance currently working on this Element
     *
     * @return bool|array Either true if validated, or an array containing
     * the error's Lexicon key and optional placeholder data
     */
    public function validateStaticFile($processor)
    {
        $file = $this->staticFileAbsolutePath;

        if ($file) {
            if ($this->pathIsProtected($file)) {
                $processor->addFieldError('static_file', $this->xpdo->lexicon('element_static_source_protected_invalid'));
            }
            if (file_exists($file)) {
                if (!is_writable($file)) {
                    $processor->addFieldError('static_file', $this->xpdo->lexicon('element_static_source_immutable'));
                }
            } else {
                $fileFragments = explode(DIRECTORY_SEPARATOR, $file);
                $fileName = array_pop($fileFragments);
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $fileDirectory = implode(DIRECTORY_SEPARATOR, $fileFragments);

                if (!$this->staticPathIsWritable($fileDirectory)) {
                    $processor->addFieldError('static_file', $this->xpdo->lexicon('file_folder_err_perms'));
                }

                // Exclude certain file types based on Element type
                switch ($fileExtension) {
                    case 'php':
                        if (!in_array($this->_class, [modSnippet::class, modPlugin::class])) {
                            $fileExtensionIsValid = false;
                            $processor->addFieldError('static_file', $this->xpdo->lexicon('file_err_ext_not_allowed', [ 'ext' => $fileExtension ]));
                        }
                        break;
                    case 'html':
                        if (in_array($this->_class, [modSnippet::class, modPlugin::class])) {
                            $fileExtensionIsValid = false;
                            $processor->addFieldError('static_file', $this->xpdo->lexicon('file_err_ext_not_allowed', [ 'ext' => $fileExtension ]));
                        }
                        break;
                }

                // Otherwise check file types based on system and/or source settings
                if (!$this->_source->checkFileType($fileName)) {
                    $processor->addFieldError('static_file', $this->xpdo->lexicon('file_err_ext_not_allowed', [ 'ext' => $fileExtension ]));
                }
            }
        }
        return true;
    }

    /**
     * Checks if the static file's target directory exists and is writable or,
     * when it does not exist, whether it would be writable (i.e., the first found
     * directory along the proposed path is writable).
     *
     * @property string $path The target directory being tested
     */
    protected function staticPathIsWritable($path): bool
    {
        $isWritable = false;

        if (is_dir($path)) {
            return is_writable($path);
        } else {
            $testDone =  false;

            // Step backward through the full path to check each directory level for existence and writability
            while (!$testDone) {
                $lastSeparatorIndex = strrpos($path, DIRECTORY_SEPARATOR);
                if ($lastSeparatorIndex !== false) {
                    $path = substr($path, 0, $lastSeparatorIndex);
                    if (is_dir($path)) {
                        /*
                            This will halt the loop on the first existing directory found; if it's writable, the rest
                            of the path is implicitly writable and this method will return true.
                        */
                        $isWritable = is_writable($path);
                        $testDone = true;
                    }
                } else {
                    $testDone = true;
                }
            }
            return $isWritable;
        }
    }
}
