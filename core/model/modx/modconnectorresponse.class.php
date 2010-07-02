<?php
/**
 * modConnectorResponse
 *
 * @package modx
 */
require_once MODX_CORE_PATH . 'model/modx/modresponse.class.php';
/**
 * Encapsulates an HTTP response from the MODx manager.
 *
 * {@inheritdoc}
 *
 * @package modx
 * @extends modResponse
 */
class modConnectorResponse extends modResponse {
    /**
     * The base location of the processors called by the connectors.
     *
     * @var string
     * @access private
     */
    protected $_directory;

    /**#@+
     * Creates a modConnectorResponse object.
     *
     * {@inheritdoc}
     */
    function __construct(modX & $modx) {
        parent :: __construct($modx);
        $this->setDirectory();
    }

    /**
     * Overrides modResponse::outputContent to provide connector-specific
     * processing.
     *
     * {@inheritdoc}
     */
    public function outputContent(array $options = array()) {
        /* variable pointer for easier access */
        $modx =& $this->modx;

        /* backwards compat */
        $error =& $this->modx->error;

        /* ensure headers are sent for proper authentication */
        if (!isset($_SERVER['HTTP_MODAUTH']) && !isset($_REQUEST['HTTP_MODAUTH'])) {
            $this->body = $modx->error->failure($modx->lexicon('access_denied'));
            
        } else if (isset($_SERVER['HTTP_MODAUTH']) && $_SERVER['HTTP_MODAUTH'] != $modx->site_id) {
            $this->body = $modx->error->failure($modx->lexicon('access_denied'));

        } else if (isset($_REQUEST['HTTP_MODAUTH']) && $_REQUEST['HTTP_MODAUTH'] != $modx->site_id) {
            $this->body = $modx->error->failure($modx->lexicon('access_denied'));

        /* verify the location and action */
        } else if (!isset($options['location']) || !isset($options['action'])) {
            $this->body = $this->modx->error->failure($modx->lexicon('action_err_ns'));
            
        } else if (empty($options['action'])) {
            $this->body = $this->modx->error->failure($modx->lexicon('action_err_ns'));

        /* execute a processor and format the response */
        } else { 
            /* prevent browsing of subdirectories for security */
            $options['action'] = str_replace('../','',$options['action']);

            /* find the appropriate processor */
            $file = $this->_directory.str_replace('\\', '/', $options['location'] . '/' . $options['action']).'.php';

            /* create scriptProperties array from HTTP GPC vars */
            if (!isset($_POST)) $_POST = array();
            if (!isset($_GET)) $_GET = array();
            $scriptProperties = array_merge($_GET,$_POST);
            if (isset($_FILES) && !empty($_FILES)) {
                $scriptProperties = array_merge($scriptProperties,$_FILES);
            }

            /* verify processor exists */
            if (!file_exists($file)) {
                $this->body = $this->modx->error->failure($this->modx->lexicon('processor_err_nf').$file);
            } else {
                /* go load the correct processor */
                $this->body = include $file;
            }
        }
        /* if files sent, this means that the browser needs it in text/plain,
         * so ignore text/json header type
         */
        if (!isset($_FILES)) {
            header("Content-Type: text/json; charset=UTF-8");
        }
        if (is_array($this->header)) {
            foreach ($this->header as $header) header($header);
        }
        if (is_array($this->body)) {
            die($this->modx->toJSON(array(
                'success' => isset($this->body['success']) ? $this->body['success'] : 0,
                'message' => isset($this->body['message']) ? $this->body['message'] : $this->modx->lexicon('error'),
                'total' => (isset($this->body['total']) && $this->body['total'] > 0)
                        ? intval($this->body['total'])
                        : (isset($this->body['errors'])
                                ? count($this->body['errors'])
                                : 1),
                'data' => isset($this->body['errors']) ? $this->body['errors'] : array(),
                'object' => isset($this->body['object']) ? $this->body['object'] : array(),
            )));
        } else {
            die($this->body);
        }
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
     * Set the physical location of the processor directory for the response handler.
     *
     * This allows for dynamic processor locations.
     *
     * @access public
     * @param string $dir The directory to set as the processors directory.
     */
    public function setDirectory($dir = '') {
        if ($dir == '') {
            $this->_directory = $this->modx->getOption('processors_path');
        } else {
            $this->_directory = $dir;
        }
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