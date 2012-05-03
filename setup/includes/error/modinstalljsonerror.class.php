<?php
/*
 * MODX Revolution
 *
 * Copyright 2006-2012 by MODX, LLC.
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
        while (@ ob_end_clean()) {}
        die($this->process($message, false, $object));
    }

    public function success($message = '', $object = null) {
        die($this->process($message, true, $object));
    }
}
