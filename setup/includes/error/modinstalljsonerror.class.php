<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */
require_once strtr(realpath(MODX_SETUP_PATH.'includes/error/modinstallerror.class.php'),'\\','/');
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */
class modInstallJSONError extends modInstallError {
    public $fields;
    public $type;

    function __construct(&$modx, $message= '', $type= 'error') {
        $this->message= $message;
        $this->fields= [];
        $this->type= $type;
        parent :: __construct($modx, $message);
    }

    public function process($message= '', $status = false, $object = null) {
        $objarray= parent :: process($message, $status, $object);
        @header("Content-Type: text/json; charset=UTF-8");
        if ($message != '') $this->message= $message;

        return json_encode([
            'message' => $this->message,
            'fields' => $this->fields,
            'type' => $this->type,
            'object' => $objarray,
            'success' => $status,
        ]);
    }

    public function addField($name, $error) {
        $this->fields[]= [
            'name' => $name,
            'error' => $error
        ];
    }

    public function getFields() {
        $f= [];
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
