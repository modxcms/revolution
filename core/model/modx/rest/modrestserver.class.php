<?php
/*
 * MODX Revolution
 *
 * Copyright 2006-2012 by MODX, LLC.
 *
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
 *
 */
/**
 * @package modx
 * @subpackage rest
 */
/**
 * An extendable class for handling REST requests.
 *
 * @package modx
 * @subpackage rest
 */
class modRestServer {
    const OPT_AUTH = 'authenticate';
    const OPT_AUTH_GET = 'authenticateGet';
    const OPT_AUTH_USER_VAR = 'authUserVar';
    const OPT_AUTH_PASS_VAR = 'authPassVar';
    const OPT_ENCODING = 'encoding';
    const OPT_ERROR_DATA_NODE = 'error_data_node';
    const OPT_ERROR_NODE = 'error_node';
    const OPT_ERROR_MESSAGE_NODE = 'error_message_node';
    const OPT_FORMAT = 'format';
    const OPT_PROCESSORS_PATH = 'processors_path';
    const OPT_REQUEST_PATH = 'request_path';
    const OPT_REQUEST_VAR = 'requestVar';
    const OPT_REALM = 'realm';
    const OPT_RENDERERS = 'renderers';

    /**
     * @var $error The current error message
     * @access protected
     */
    protected $error = false;

    /**
     * @param modX $modx A reference to the modX object
     * @param array $config An array of configuration options
     */
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;
        $this->config = array_merge(array(
            modRestServer::OPT_AUTH => true,
            modRestServer::OPT_AUTH_GET => false,
            modRestServer::OPT_AUTH_USER_VAR => 'user',
            modRestServer::OPT_AUTH_PASS_VAR => 'password',
            modRestServer::OPT_ENCODING => 'UTF-8',
            modRestServer::OPT_FORMAT => 'xml',
            modRestServer::OPT_PROCESSORS_PATH => '',
            modRestServer::OPT_REQUEST_VAR => 'p',
            modRestServer::OPT_REALM => 'MODX',
            modRestServer::OPT_RENDERERS => 'renderers',
            modRestServer::OPT_ERROR_DATA_NODE => 'data',
            modRestServer::OPT_ERROR_NODE => 'error',
            modRestServer::OPT_ERROR_MESSAGE_NODE => 'message',
        ),$config);
    }

    /**
     * Handles the REST request and loads the correct processor. Checks for
     * authentication should it be a type not equal to GET if authenticate is
     * set to true, or always if authenticateGet is set to true.
     *
     * @access public
     * @return string
     */
    public function handle() {
        $scriptProperties = array();
        $scriptProperties[modRestServer::OPT_REQUEST_PATH] = $this->computePath();

        $output = '';
        if (file_exists($scriptProperties[modRestServer::OPT_REQUEST_PATH])) {
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                if ($this->config[modRestServer::OPT_AUTH_GET]) {
                    $result = $this->authenticate();
                    if ($result !== true) {
                        return $result;
                    }
                }

                $scriptProperties = array_merge($scriptProperties,$_GET);
            } else {
                if ($this->config[modRestServer::OPT_AUTH]) {
                    $result = $this->authenticate();
                    if ($result !== true) {
                        return $result;
                    }
                }

            }
            $modx =& $this->modx;
            $output = include $scriptProperties[modRestServer::OPT_REQUEST_PATH];

        } else {
            return $this->error('404 Not Found',$scriptProperties);
        }

        return $output;
    }

    /**
     * Computes the path for the REST request
     *
     * @access public
     * @return string The absolute path to the processor to load
     */
    public function computePath() {
        $path = $this->config[modRestServer::OPT_PROCESSORS_PATH];
        $path .= trim($_REQUEST[$this->config[modRestServer::OPT_REQUEST_VAR]],'/').'/';
        $path .= strtolower($_SERVER['REQUEST_METHOD']).'.php';
        return $path;
    }


    /**
     * Handles basic authentication for the server
     *
     * @todo Add an optional usergroup check
     *
     * @return boolean True if successful.
     */
    public function authenticate() {
        $this->modx->getService('lexicon','modLexicon');
        $this->modx->lexicon->load('user');

        if (empty($_REQUEST[$this->config[modRestServer::OPT_AUTH_USER_VAR]])) {
            return $this->deny($this->modx->lexicon('user_err_ns'));
        }
        if (empty($_REQUEST[$this->config[modRestServer::OPT_AUTH_PASS_VAR]])) {
            return $this->deny($this->modx->lexicon('user_err_not_specified_password'));
        }

        $user = $this->modx->getObject('modUser',array(
            'username' => $_REQUEST[$this->config[modRestServer::OPT_AUTH_USER_VAR]],
        ));
        if (empty($user)) return $this->deny($this->modx->lexicon('user_err_nf'));

        if (!$user->passwordMatches($_REQUEST[$this->config[modRestServer::OPT_AUTH_PASS_VAR]])) {
            return $this->deny($this->modx->lexicon('user_err_password'));
        }
        return true;
    }

    /**
     * Deny access and send a 401.
     *
     * @param string $message
     * @param array $data
     * @return string
     */
    public function deny($message,array $data = array()) {
        return $this->error($message,$data,'401');
    }

    /**
     * Handles success messages
     *
     * @param array|xPDOObject $data The data to pass and encode
     * @param string $root
     * @return string The encoded message
     */
    public function success($data,$root = '') {
        header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
        return $this->encode($data,$root);
    }

    /**
     * Handles error messages
     *
     * @access public
     * @param string $message An error message
     * @param array|xPDOObject $data Any additional data
     * @param string $type The type of the error message
     * @return string
     */
    public function error($message = '',$data = array(),$type = '404') {
        $this->error = true;
        if (method_exists($this,'_err'.$type)) {
            $errType = '_err'.$type;
            $this->$errType();
        } else {
            $this->_err404();
        }
        return $this->encode(array(
            $this->config[modRestServer::OPT_ERROR_MESSAGE_NODE] => $message,
            $this->config[modRestServer::OPT_ERROR_DATA_NODE] => $data,
        ),'<'.$this->config[modRestServer::OPT_ERROR_NODE].'>');
    }

    /**
     * Encodes the data to the specified format. Defaults to XML.
     *
     * @access public
     * @param array|xPDOObject $data
     * @param string $root
     * @return string The encoded message
     */
    public function encode($data,$root = '') {
        $output = '';

        $format = $this->modx->getOption(modRestServer::OPT_FORMAT,$_REQUEST,$this->config[modRestServer::OPT_FORMAT]);
        switch ($format) {
            case 'json':
                header('Content-Type: application/javascript');
                if (is_array($data)) {
                    $list = array();
                    foreach ($data as $k => $v) {
                        $list[$k.'s'] = $v;
                    }
                } else {
                    $list = array($root => $data->toArray());
                }
                $output = $this->modx->toJSON($list);
                break;
            case 'xml':
            default:
                header('Content-Type: text/xml');
                if (is_array($data)) {
                    $list = $data;
                } else if ($data instanceof xPDOObject) {
                    $list = $data->toArray();
                }
                $output = '<?xml version="1.0" encoding="'.$this->config['encoding'].'"?>'.
                    "\n{$root}\n";
                $output .= $this->array2xml($list);
                $endRootTag = '</'.substr($root,1,strpos($root,' ')-1).'>';
                $output .= "{$endRootTag}\n";
                break;
        }
        return $output;
    }

    /**
     * Sets HTTP 204 response headers
     * @param string $output The outputted response to send
     * @return string
     */
    private function _err204($output = '') {
        header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
        return $output;
    }

    /**
     * Sets HTTP 400 response headers
     * @param string $output The outputted response to send
     * @return string
     */
    private function _err400($output = '') {
        header($_SERVER['SERVER_PROTOCOL'].' 400 Bad Request');
        return '';
    }

    /**
     * Sets HTTP 401 response headers
     * @return string
     */
    private function _err401() {
        header('WWW-Authenticate: Basic realm="'.$this->config['realm'].'"');
        header($_SERVER['SERVER_PROTOCOL'].' 401 Unauthorized');
        return '';
    }

    /**
     * Sets HTTP 404 response headers
     * @param array $scriptProperties An array of properties
     * @return string
     */
    private function _err404($scriptProperties = array()) {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        $output = '<h2>404 Not Found</h2>';
        if (!empty($scriptProperties)) {
            $output .= '<p>'.$scriptProperties['called'].' is not a valid request.</p>';
        }

        return $output;
    }

    /**
     * Sets HTTP 405 response headers
     * @param string $allowed A comma-separated list of allowed protocols
     * @return string
     */
    private function _err405($allowed = 'GET, HEAD') {
        header($_SERVER['SERVER_PROTOCOL'].' 405 Method Not Allowed');
        header('Allow: '.$allowed);
        return '';
    }

    /**
     * Sets HTTP 406 response headers
     * @param string $output The outputted response to send
     * @return string
     */
    private function _err406($output = '') {
        header($_SERVER['SERVER_PROTOCOL'].' 406 Not Acceptable');
        $output = join(', ', array_keys($this->config[modRestServer::OPT_RENDERERS]));
        return $output;
    }

    /**
     * Sets HTTP 411 response headers
     * @param string $output The outputted response to send
     * @return string
     */
    private function _err411($output = '') {
        header($_SERVER['SERVER_PROTOCOL'].' 411 Length Required');
        return '';
    }

    /**
     * Sets HTTP 500 response headers
     * @param string $output The outputted response to send
     * @return string
     */
    private function _err500($output = '') {
        header($_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error');
        return '';
    }

    /**
     * Converts an array to xml
     *
     * @access protected
     * @param array $array
     * @param integer $level
     * @return string
     */
    protected function array2xml($array,$level=1) {
        $xml = '';
        foreach ($array as $key=>$value) {
            $key = strtolower($key);
            if (is_array($value)) {
                $multi_tags = false;
                foreach($value as $key2=>$value2) {
                    if (is_array($value2)) {
                        $xml .= str_repeat("\t",$level)."<$key>\n";
                        $xml .= $this->array2xml($value2,$level+1);
                        $xml .= str_repeat("\t",$level)."</$key>\n";
                        $multi_tags = true;
                    } else {
                        if (trim($value2)!='') {
                            if (htmlspecialchars($value2)!=$value2) {
                                $xml .= str_repeat("\t",$level).
                                        "<$key><![CDATA[$value2]]>".
                                        "</$key>\n";
                            } else {
                                $xml .= str_repeat("\t",$level).
                                        "<$key>$value2</$key>\n";
                            }
                        }
                        $multi_tags = true;
                    }
                }
                if (!$multi_tags and count($value)>0) {
                    $xml .= str_repeat("\t",$level)."<$key>\n";
                    $xml .= $this->array2xml($value,$level+1);
                    $xml .= str_repeat("\t",$level)."</$key>\n";
                }
            } else {
                if (trim($value)!='') {
                    if (htmlspecialchars($value)!=$value) {
                        $xml .= str_repeat("\t",$level)."<$key>".
                                "<![CDATA[$value]]></$key>\n";
                    } else {
                        $xml .= str_repeat("\t",$level).
                                "<$key>$value</$key>\n";
                    }
                }
            }
        }
        return $xml;
    }
}