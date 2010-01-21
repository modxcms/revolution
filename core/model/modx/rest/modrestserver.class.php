<?php
/*
 * MODx Revolution
 *
 * Copyright 2006, 2007, 2008, 2009, 2010 by the MODx Team.
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
 */
/**
 * An extendable class for handling REST requests.
 *
 * @package modx
 * @subpackage rest
 */
class modRestServer {
    /**
     * @var $error The current error message
     * @access protected
     */
    protected $error = false;

    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;
        $this->config = array_merge(array(
            'authenticate' => true,
            'authenticateGet' => false,
            'authUserVar' => 'user',
            'authPassVar' => 'password',
            'encoding' => 'UTF-8',
            'format' => 'xml',
            'processors_path' => '',
            'requestVar' => 'p',
            'realm' => 'MODx',
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
        $scriptProperties['request_path'] = $this->computePath();

        $output = '';
        if (file_exists($scriptProperties['request_path'])) {
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                if ($this->config['authenticateGet'] && !$this->authenticate()) {
                    return $this->error('Permission Denied!',array($scriptProperties),'401');
                }

                $scriptProperties = array_merge($scriptProperties,$_GET);
            } else if ($tmp = file_get_contents('php://input')) {
                if ($this->config['authenticate'] && !$this->authenticate()) {
                    return $this->error('Permission Denied!',array($scriptProperties),'401');
                }

            }
            $modx =& $this->modx;
            $output = include $scriptProperties['request_path'];

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
        $path = $this->config['processors_path'];
        $path .= trim($_REQUEST[$this->config['requestVar']],'/').'/';
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
        if (empty($_REQUEST[$this->config['authUserVar']])) return false;
        if (empty($_REQUEST[$this->config['authPassVar']])) return false;
        $user = $this->modx->getObject('modUser',array(
            'username' => $_REQUEST[$this->config['authUserVar']],
        ));
        if (empty($user)) return false;

        if ($user->get('password') != md5($_REQUEST[$this->config['authPassVar']])) return false;
        return true;
    }

    /**
     * Handles success messages
     *
     * @param array/xPDOObject $data The data to pass and encode
     * @param string $root
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
     * @param array/xPDOObject $data Any additional data
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
            'message' => $message,
            'data' => $data,
        ),'<error>');
    }

    /**
     * Encodes the data to the specified format. Defaults to XML.
     *
     * @access public
     * @param array/xPDOObject $data
     * @param string $root
     * @return string
     */
    public function encode($data,$root = '') {
        $output = '';

        $format = $this->modx->getOption('format',$_REQUEST,$this->config['format']);
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



    private function _err204($output = '') {
        header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
        return $output;
    }

    private function _err400($output = '') {
        header($_SERVER['SERVER_PROTOCOL'].' 400 Bad Request');
        return '';
    }

    private function _err401() {
        header('WWW-Authenticate: Basic realm="'.$this->config['realm'].'"');
        header($_SERVER['SERVER_PROTOCOL'].' 401 Unauthorized');
        return '';
    }

    private function _err404($scriptProperties = array()) {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        $output = '<h2>404 Error Not Found</h2>';
        if (!empty($scriptProperties)) {
            $output .= '<p>'.$scriptProperties['called'].' is not a valid request.</p>';
        }

        return $output;
    }

    private function _err405($allowed = 'GET, HEAD') {
        header($_SERVER['SERVER_PROTOCOL'].' 405 Method Not Allowed');
        header('Allow: '.$allowed);
        return '';
    }

    private function _err406($output = '') {
        header($_SERVER['SERVER_PROTOCOL'].' 406 Not Acceptable');
        $output = join(', ', array_keys($this->config['renderers']));
        return $output;
    }

    private function _err411($output = '') {
        header($_SERVER['SERVER_PROTOCOL'].' 411 Length Required');
        return '';
    }

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