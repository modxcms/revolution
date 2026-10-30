<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/** @phpcs:disable PSR2.Methods.MethodDeclaration.Underscore */

namespace MODX\Revolution\Processors;

use MODX\Revolution\modX;
use MODX\Revolution\Utilities\Sanitizers\modStringSanitizer;
use MODX\Revolution\Utilities\Converters\modStringConverter;

/**
 * Abstracts a MODX processor, handling its response and error formatting.
 *
 * @package MODX\Revolution
 */
abstract class Processor
{
    // Status message types, currently aligned with ExtJS message types
    /** Status message type: Error */
    public const STATUS_TYPE_ERROR = 'error';
    /** Status message type: Warning */
    public const STATUS_TYPE_WARN = 'warning';
    /** Status message type: Info */
    public const STATUS_TYPE_INFO = 'info';
    /** Status message type: Question */
    public const STATUS_TYPE_ASK = 'question';

    // Additional non-ExtJS-specific message types
    /** Status message type: Success */
    public const STATUS_TYPE_SUCCESS = 'success';

    /**
     * A reference to the modX object.
     * @var modX $modx
     */
    public $modx = null;
    /**
     * The absolute path to this processor
     * @var string $path
     */
    public $path = '';
    /**
     * The array of properties being passed to this processor
     * @var array $properties
     */
    public $properties = [];
    /**
     * The Permission to use when checking against
     * @var string $permission
     */
    public $permission = '';

    /** A reference to the modStringSanitizer service */
    public modStringSanitizer $stringSanitizers;

    /** A reference to the modStringConverter service */
    public modStringConverter $stringConverters;

    /** @var bool $useStatus Whether to look for and use custom status message data */
    protected bool $useStatus = false;

    /** @var array $statusConfig Use to persist message configuration data when it needs to be established in a child processor class but the actual status/success/failure is emitted by its parent class */
    protected array $statusConfig = [];

    /**
     * Creates a modProcessor object.
     *
     * @param modX $modx A reference to the modX instance
     * @param array $properties An array of properties
     */
    public function __construct(modX $modx, array $properties = [])
    {
        $this->modx =& $modx;
        if (!$this->modx->services->has(modStringSanitizer::class)) {
            $this->modx->services->add(modStringSanitizer::class, fn() => new modStringSanitizer($this->modx));
        }
        if (!$this->modx->services->has(modStringConverter::class)) {
            $this->modx->services->add(modStringConverter::class, fn() => new modStringConverter($this->modx));
        }
        $this->stringSanitizers = $this->modx->services->get(modStringSanitizer::class);
        $this->stringConverters = $this->modx->services->get(modStringConverter::class);
        $this->setProperties($properties);
    }

    /**
     * Set the path of the processor
     * @param string $path The absolute path
     * @return void
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Set the runtime properties for the processor
     * @param array $properties The properties, in array and key-value form, to run on this processor
     * @param bool $merge Indicates if properties should be merged with existing ones
     *
     * @return void
     */
    public function setProperties($properties, $merge = true)
    {
        unset($properties['HTTP_MODAUTH']);
        $this->properties = $merge ? array_merge($this->properties, $properties) : $properties;
    }

    /**
     * Completely unset a property from the properties array
     * @param string $key
     * @return void
     */
    public function unsetProperty($key)
    {
        unset($this->properties[$key]);
    }

    /**
     * Return true here to allow access to this processor.
     *
     * @return boolean
     */
    public function checkPermissions()
    {
        return true;
    }

    /**
     * Can be used to provide custom methods prior to processing. Return true to tell MODX that the Processor
     * initialized successfully. If you return anything else, MODX will output that return value as an error message.
     *
     * @return boolean
     */
    public function initialize()
    {
        return true;
    }

    /**
     * Load a collection of Language Topics for this processor.
     * Override this in your derivative class to provide the array of topics to load.
     * @return array
     */
    public function getLanguageTopics()
    {
        return [];
    }

    /**
     * Return a success message from the processor.
     * @param string $msg
     * @param mixed $object
     * @return array|string
     */
    public function success($msg = '', $object = null)
    {
        return $this->modx->error->success($msg, $object);
    }

    /**
     * Return a failure message from the processor.
     * @param string $msg
     * @param mixed $object
     * @return array|string
     */
    public function failure($msg = '', $object = null)
    {
        return $this->modx->error->failure($msg, $object);
    }

    /**
     * Return a type-specific message with optional customizations from the processor.
     *
     * @param string $message The message to send.
     * @param string $messageWindowTitle Optional title to replace the default,
     * generic title for the specified message type.
     * @param string $messageType Optional indicator of the message type
     * (error, warn, info, etc)
     * @param bool $messageIsFormatted Indicates whether message contains
     * and should render html
     * @param object|array|string $object An object to send back to the output.
     * @return string|array The status response
     */
    public function status(
        $message = '',
        $messageWindowTitle = '',
        $messageType = self::STATUS_TYPE_ERROR,
        $messageIsFormatted = false,
        $object = null
    ) {
        $this->useStatus = true;
        if (!$messageIsFormatted) {
            $message = $this->stringSanitizers->stripHTML($message);
        } else {
            $message = $this->stringSanitizers->stripHTML(
                $message,
                modX::MGR_MESSAGES_ALLOWED_TAGS,
                modX::MGR_MESSAGES_ALLOWED_ATTRS
            );
            $message = $this->stringConverters->htmlToJSON($message);
        }

        $hasCustomMessageOptions = !empty($messageWindowTitle) || $messageType !== self::STATUS_TYPE_ERROR || $messageIsFormatted;

        if ($hasCustomMessageOptions) {
            $this->modx->error->messageConfig = $this->setCustomMessageOptions($messageWindowTitle, $messageType, $messageIsFormatted);
        }
        $status = $messageType !== self::STATUS_TYPE_ERROR ? 'success' : 'failure';

        return $this->modx->error->$status($message, $object);
    }

    /**
     * Use in child processors to make custom message configuration data available
     * to the parent processor that emits the status message.
     *
     * @param array $config An optional set of customization specifications to tailor the
     * message output. Config options include:
     * - messageType: (string) The type of message to output. Options include the status
     * constants defined in the base processor (i.e., Processor::STATUS_TYPE_INFO, etc.).
     * - messageWindowTitle: (string) Overrides the default window title
     * - messageIsFormatted: (bool) Whether the message source is html-formatted
     * (must be set to true to preserve formatting).
     */
    public function forwardStatus($config = []): void
    {
        $this->useStatus = true;
        $this->statusConfig = $config;
    }

    /**
     * Return whether or not the processor has errors
     * @return boolean
     */
    public function hasErrors()
    {
        return $this->modx->error->hasError();
    }

    /**
     * Add an error to the field
     * @param string $key
     * @param string $message
     * @return void
     */
    public function addFieldError($key, $message = '')
    {
        $this->modx->error->addField($key, $message);
    }

    /**
     * Return the proper instance of the derived class. This can be used to override how MODX loads a processor
     * class; for example, when handling derivative classes with class_key settings.
     *
     * @static
     * @param modX $modx A reference to the modX object.
     * @param string $className The name of the class that is being requested.
     * @param array $properties An array of properties being run with the processor
     * @return Processor The class specified by $className
     */
    public static function getInstance(modX $modx, $className, $properties = [])
    {
        /** @var Processor $processor */
        $processor = new $className($modx, $properties);
        return $processor;
    }

    /**
     * Run the processor and return the result. Override this in your derivative class to provide custom functionality.
     * Used here for pre-2.2-style processors.
     *
     * @return mixed
     */
    abstract public function process();

    /**
     * Run the processor, returning a ProcessorResponse object.
     * @return ProcessorResponse
     */
    public function run()
    {
        if (!$this->checkPermissions()) {
            $o = $this->failure($this->modx->lexicon('permission_denied_processor', array(
                'action' => preg_replace('/[^\w\-_\/]+/i', '', $this->getProperty('action')),
                'permission' => ($this->permission != '') ? $this->permission : '- unknown -'
            )));
        } else {
            $topics = $this->getLanguageTopics();
            foreach ($topics as $topic) {
                $this->modx->lexicon->load($topic);
            }

            $initialized = $this->initialize();
            if ($initialized !== true) {
                $o = $this->failure($initialized);
            } else {
                $o = $this->process();
            }
        }
        $response = new ProcessorResponse($this->modx, $o);
        return $response;
    }

    /**
     * Get a specific property.
     * @param string $k
     * @param mixed $default
     * @return mixed
     */
    public function getProperty($k, $default = null)
    {
        return array_key_exists($k, $this->properties) ? $this->properties[$k] : $default;
    }

    /**
     * Set a property value
     *
     * @param string $k
     * @param mixed $v
     * @return void
     */
    public function setProperty($k, $v)
    {
        $this->properties[$k] = $v;
    }

    /**
     * Special helper method for handling checkboxes. Only set value if passed or $force is true, and check for a
     * not empty value or string 'false'.
     *
     * @param string $k
     * @param boolean $force
     * @return int|null
     */
    public function setCheckbox($k, $force = false)
    {
        $v = null;
        if ($force || isset($this->properties[$k])) {
            $v = empty($this->properties[$k]) || $this->properties[$k] === 'false' ? 0 : 1;
            $this->setProperty($k, $v);
        }
        return $v;
    }

    /**
     * Get an array of properties for this processor
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Sets default properties that only are set if they don't already exist in the request
     *
     * @param array $properties
     * @return array The newly merged properties array
     */
    public function setDefaultProperties(array $properties = [])
    {
        $this->properties = array_merge($properties, $this->properties);
        return $this->properties;
    }

    /**
     * Return arrays of objects (with count) converted to JSON.
     *
     * The JSON result includes two main elements, total and results. This format is used for list
     * results.
     *
     * @access public
     * @param array $array An array of data objects.
     * @param mixed $count The total number of objects. Used for pagination.
     * @return string The JSON output.
     */
    public function outputArray(array $array, $count = false)
    {
        if ($count === false) {
            $count = count($array);
        }
        $output = json_encode([
            'success' => true,
            'total' => $count,
            'results' => $array
        ], JSON_INVALID_UTF8_SUBSTITUTE);

        if ($output === false) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Processor failed creating output array due to JSON error ' . json_last_error());
            return json_encode(['success' => false]);
        }
        return $output;
    }

    /**
     * Converts PHP to JSON with JavaScript literals left in-tact.
     *
     * JSON does not allow JavaScript literals, but this function encodes certain identifiable
     * literals and decodes them back into literals after modX::toJSON() formats the data.
     *
     * @access public
     * @param mixed $data The PHP data to be converted.
     * @return string The extended JSON-encoded string.
     */
    public function toJSON($data)
    {
        if (is_array($data)) {
            array_walk_recursive($data, [&$this, '_encodeLiterals']);
        }
        return $this->_decodeLiterals(json_encode($data, JSON_INVALID_UTF8_SUBSTITUTE));
    }

    /**
     * Prepare formatting and titling config for optional customizations to be
     * sent via $this->status
     *
     * @param string $messageWindowTitle Optional title to replace the default, generic error title
     * @param string $messageType Optional indicator of the message type (error, warn, info)
     * @param bool $messageIsFormatted Indicates whether message contains and should render html
     * @return array The prepared configuration
     */
    private function setCustomMessageOptions(
        string $messageWindowTitle = '',
        string $messageType = 'error',
        bool $messageIsFormatted = false
    ): array {
        $options = [];
        if (empty($messageWindowTitle) && $messageType === 'error' && empty($messageIsFormatted)) {
            return $options;
        }
        $options['messageConfig'] = [];
        switch (true) {
            case $messageIsFormatted:
                $options['messageConfig']['messageIsFormatted'] = $messageIsFormatted;
                // fall through to keep building
            case $messageType !== 'error':
                $options['messageConfig']['messageType'] = $messageType;
                // fall through to keep building
            case !empty($messageWindowTitle):
                $options['messageConfig']['messageWindowTitle'] = $messageWindowTitle;
            // no default
        }
        return $options;
    }

    /**
     * Encodes certain JavaScript literal strings for later decoding.
     *
     * @access protected
     * @param mixed &$value A reference to the value to be encoded if it is identified as a literal.
     * @param integer|string $key The array key corresponding to the value.
     */
    protected function _encodeLiterals(&$value, $key)
    {
        if (is_string($value)) {
            /* properly handle common literal structures */
            if (
                strpos($value, 'function(') === 0
                || strpos($value, 'new Function(') === 0
                || strpos($value, 'Ext.') === 0
            ) {
                $value = '@@' . base64_encode($value) . '@@';
            }
        }
    }

    /**
     * Decodes strings encoded by _encodeLiterals to restore JavaScript literals.
     *
     * @access protected
     * @param string $string The JSON-encoded string with encoded literals.
     * @return string The JSON-encoded string with literals restored.
     */
    protected function _decodeLiterals($string)
    {
        $pattern = '/"@@(.*?)@@"/';
        $string = preg_replace_callback(
            $pattern,
            function ($matches) {
                return base64_decode($matches[1]);
            },
            $string
        );
        return $string;
    }

    /**
     * Processes a response from a Plugin Event invocation
     *
     * @param array|string $response The response generated by the invokeEvent call
     * @param string $separator The separator for each event response
     * @return string The processed response.
     */
    public function processEventResponse($response, $separator = "\n")
    {
        if (is_array($response)) {
            $result = false;
            foreach ($response as $msg) {
                if (!empty($msg)) {
                    $result[] = $msg;
                }
            }
            if ($result) {
                $result = implode($separator, $result);
            }
        } else {
            $result = $response;
        }
        return $result;
    }
}
