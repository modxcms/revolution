<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
require_once strtr(realpath(MODX_SETUP_PATH.'includes/error/modinstallerror.class.php'),'\\','/');
/**
 * @package modx
 * @subpackage setup
 */
class modInstallJSONError extends modInstallError {
    public $fields;
    public $type;

    function __construct(&$modx, $message= '', $type= 'error') {
        $this->message= $message;
        $this->fields= array ();
        $this->type= $type;
        parent :: __construct($modx, $message);
    }

    public function process($message= '', $status = false, $object = null) {
        $objarray= parent :: process($message, $status, $object);
        @header("Content-Type: text/json; charset=UTF-8");
        if ($message != '') $this->message= $message;

        return json_encode(array (
            'message' => $this->message,
            'fields' => $this->fields,
            'type' => $this->type,
            'object' => $objarray,
            'success' => $status,
        ));
    }

    public function addField($name, $error) {
        $this->fields[]= array (
            'name' => $name,
            'error' => $error
        );
    }

    public function getFields() {
        $f= array ();
        foreach ($this->fields as $fi) $f[]= $fi['name'];
        return $f;
    }

    public function hasError() {
        return count($this->fields) > 0 || ($this->message != '' && $this->type == 'error');
    }

    public function setType($type= 'error') {
        $this->type= $type;
    }

    public function failure($message = '', $object = null) {
        while (ob_get_level() && @ob_end_clean()) {}
        die($this->process($message, false, $object));
    }

    public function success($message = '', $object = null) {
        die($this->process($message, true, $object));
    }
}
