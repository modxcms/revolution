<?php
/**
 * modProcessor
 *
 * @package modx
 */
/**
 * Abstracts a MODx processor
 *
 * {@inheritdoc}
 *
 * @package modx
 */
class modProcessor {
    public $modx = null;
    public $path = '';
    public $properties = array();

    /**
     * Creates a modProcessor object.
     *
     * {@inheritdoc}
     */
    function __construct(modX & $modx) {
        $this->modx =& $modx;
    }


    public function setPath($path) {
        $this->path = $path;
    }
    public function setProperties($properties) {
        $this->properties = $properties;
    }

    public function run() {
        $modx =& $this->modx;
        $scriptProperties = $this->properties;
        $o = include $this->path;
        return $o;
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
    public function outputArray(array $array,$count = false) {
        if (!is_array($array)) return false;
        if ($count === false) { $count = count($array); }
        return '({"total":"'.$count.'","results":'.$this->modx->toJSON($array).'})';
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
    public function toJSON($data) {
        if (is_array($data)) {
            array_walk_recursive($data, array(&$this, '_encodeLiterals'));
        }
        return $this->_decodeLiterals($this->modx->toJSON($data));
    }

    /**
     * Encodes certain JavaScript literal strings for later decoding.
     *
     * @access protected
     * @param mixed &$value A reference to the value to be encoded if it is identified as a literal.
     * @param integer|string $key The array key corresponding to the value.
     */
    protected function _encodeLiterals(&$value, $key) {
        if (is_string($value)) {
            /* properly handle common literal structures */
            if (strpos($value, 'function(') === 0
             || strpos($value, 'this.') === 0
             || strpos($value, 'new Function(') === 0
             || strpos($value, 'Ext.') === 0) {
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
    protected function _decodeLiterals($string) {
        $pattern = '/"@@(.*?)@@"/';
        $string = preg_replace_callback(
            $pattern,
            create_function('$matches', 'return base64_decode($matches[1]);'),
            $string
        );
        return $string;
    }
}