<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

require_once MODX_CORE_PATH . 'model/modx/modresponse.class.php';
/**
 * Encapsulates an HTTP response from the MODX manager.
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

    public $responseCode = 200;

    protected $_responseCodes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    );
    /**
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
        /* prevent browsing of subdirectories for security */
        $target = preg_replace('/[\.]{2,}/', '', htmlspecialchars($options['action']));

        $siteId = $this->modx->user->getUserToken($this->modx->context->get('key'));
        $isLogin = $target == 'login' || $target == 'security/login';

        /* Block the user if there's no user token for the current context, and permissions are in fact required */
        if (empty($siteId) && (!defined('MODX_REQP') || MODX_REQP === TRUE)) {
            $this->responseCode = 401;
            $this->body = $modx->error->failure($modx->lexicon('access_denied'),array('code' => 401));
        }
        /* Make sure we've got a token */
        elseif (!$isLogin && !isset($_SERVER['HTTP_MODAUTH']) && (!isset($_REQUEST['HTTP_MODAUTH']) || empty($_REQUEST['HTTP_MODAUTH']))) {
            $this->responseCode = 401;
            $this->body = $modx->error->failure($modx->lexicon('access_denied'),array('code' => 401));
        }
        /* If the token was passed as a request header (like in the manager), check if it's right */
        else if (!$isLogin && isset($_SERVER['HTTP_MODAUTH']) && $_SERVER['HTTP_MODAUTH'] != $siteId) {
            $this->responseCode = 401;
            $this->body = $modx->error->failure($modx->lexicon('access_denied'),array('code' => 401));
        }
        /* If the token was passed a request variable, check if it's right */
        else if (!$isLogin && isset($_REQUEST['HTTP_MODAUTH']) && $_REQUEST['HTTP_MODAUTH'] != $siteId) {
            $this->responseCode = 401;
            $this->body = $modx->error->failure($modx->lexicon('access_denied'), array('code' => 401));
        }
        /* verify the location and action */
        /*else if (!isset($options['location']) || !isset($options['action'])) {
            $this->responseCode = 404;
            $this->body = $this->modx->error->failure($modx->lexicon('action_err_ns'),array('code' => 404));

        }*/
        /* If we don't have an action, 404 out */
        else if (empty($options['action'])) {
            $this->responseCode = 404;
            $this->body = $this->modx->error->failure($modx->lexicon('action_err_ns'), array('code' => 404));
        }
        /* execute a processor and format the response */
        else {
            /* create scriptProperties array from HTTP GPC vars */
            if (!isset($_POST)) $_POST = array();
            if (!isset($_GET) || $isLogin) $_GET = array();
            $scriptProperties = array_merge($_GET,$_POST);
            if (isset($_FILES) && !empty($_FILES)) {
                $scriptProperties = array_merge($scriptProperties,$_FILES);
            }

            /* run processor */
            $this->response = $this->modx->runProcessor($target,$scriptProperties,$options);
            if (!$this->response) {
                $this->responseCode = 404;
                $this->body = $this->modx->error->failure($this->modx->lexicon('processor_err_nf',array(
                    'target' => $target,
                )));
            } else {
                $this->body = $this->response->getResponse();
            }
        }
        /* if files sent, this means that the browser needs it in text/plain,
         * so ignore text/json header type
         */
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            header("Content-Type: application/json; charset=UTF-8");
            $message = 'OK';
            if (array_key_exists($this->responseCode,$this->_responseCodes)) {
                $message = $this->_responseCodes[$this->responseCode];
            }
            header('Status: '.$this->responseCode.' '.$message);
            header('Version: ' . $_SERVER['SERVER_PROTOCOL']);
        }
        if (is_array($this->header)) {
            foreach ($this->header as $header) header($header);
        }
        if (is_array($this->body)) {
            @session_write_close();
            $json = $this->modx->toJSON(array(
                'success' => isset($this->body['success']) ? $this->body['success'] : 0,
                'message' => isset($this->body['message']) ? $this->body['message'] : $this->modx->lexicon('error'),
                'total' => (isset($this->body['total']) && $this->body['total'] > 0)
                    ? intval($this->body['total'])
                    : (isset($this->body['errors'])
                        ? count($this->body['errors'])
                        : 1),
                'data' => isset($this->body['errors']) ? $this->body['errors'] : array(),
                'object' => isset($this->body['object']) ? $this->body['object'] : array(),
            ));

            if (!empty($_GET['callback'])) {
                $json = $modx->stripTags($_GET['callback']) . '(' . $json . ')';
            }
            die($json);
        } else {
            @session_write_close();
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
            function ($matches) { return base64_decode($matches[1]); },
            $string
        );
        return $string;
    }
}
