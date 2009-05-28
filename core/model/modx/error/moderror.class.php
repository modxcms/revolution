<?php
/*
 * MODx Revolution
 * 
 * Copyright 2006, 2007, 2008, 2009 by the MODx Team.
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

/**
 * Error handler for request/response processing.
 *
 * @package modx
 * @subpackage error
 */

/**
 * Abstract class for error handling and validation for requests.
 *
 * @abstract Implement a derivative of this class for error handling and
 * validation of any kind of MODx request.
 * @package modx
 * @subpackage error
 */
class modError {
    /**
    * @var array The array of errors
    */
    var $errors;
    /**
    * @var string The error message to output.
    */
    var $message;
    /**
    * @var modX A reference to the $modx object.
    */
    var $modx;
    /**
    * @var integer The total number of errors.
    */
    var $total = 0;
    /**
     * @var boolean Indicates failure or success.
     */
    var $status = false;
    var $_objects = array();

    function modError(& $modx, $message = '') {
        $this->__construct($modx, $message);
    }
    function __construct(& $modx, $message = '') {
        $this->modx = & $modx;
        $this->message = $message;
        $this->errors = array ();
    }
    
    /**
     * Adds an object to the validation queue.
     * @access public
     * @param xPDOObject $obj An xPDOObject to validate.
     */
    function addObjectToValidate(&$obj) {
        if (is_object($obj) && is_a($obj,'xPDOObject')) {
            $this->_objects[]= $obj;
        }
    }
    
    /**
     * Checks validation, and if any errors are found, returns them. Error
     * handlers that derive from this can determine their own behaviour should
     * errors be found.
     * @access public
     * @param xPDOObject/array $objs An xPDOObject or array of xPDOObjects to
     * add to the validation queue.
     * @return string The validation message returned.
     */
    function checkValidation($objs= array()) {
        if (is_object($objs)) {
            $this->addObjectToValidate($objs);
        }
        else if (is_array($objs) && !empty($objs)) {
            foreach ($objs as $obj) {
                $this->addObjectToValidate($obj);
            }
        }
        return $this->_validate();
    }
    
    /**
     * Grabs formatted validation messages for all objects in the validation
     * queue.
     * @access private
     * @return string The compiled validation message returned.
     */
    function _validate() {
        $s = '';
        foreach ($this->_objects as $obj) {
            if ($validator= $obj->getValidator()) {
                $messages= $validator->getMessages();
                if (!empty($messages)) {
                    foreach ($messages as $message) {
                        $s .= $message['message'].'<br />'."\n";
                    }
                }
            }
        }
        return $s;
    }

    /**
     * Process errors and return a proper output value.
     *
     * @param string $message The error message to output.
     * @param boolean $status Whether or not the action is a success or failure.
     * @param object|array $object The object to send back to output.
     * @return string|object|array The transformed object data array.
     */
    function process($message = '', $status = false, $object = null) {
        if (isset($this->modx->registry) && $this->modx->registry->isLogging()) {
            $this->modx->registry->resetLogging();
        }
        if ($status === true) {
            $s = $this->_validate();
            if ($s !== '') {
                $status = false;
                $message = $s;
            }
        }
        $this->status = (boolean) $status;
        
        if ($message != '') {
            $this->message = $message;
        }
        $objarray = array ();
        if (is_array($object)) {
            $obj = reset($object);
            if (is_object($obj) && is_a($obj, 'xPDOObject')) {
                $this->total = count($object);
            }
            unset ($obj);
        }
        $objarray = $this->toArray($object);
        return array (
            'success' => $status,
            'message' => $this->message,
            'total' => isset ($this->total) && $this->total != 0 ? $this->total : count($this->errors),
            'errors' => $this->errors,
            'object' => $objarray,
        );
    }

    /**
     * Add a specific field error to the error queue.
     *
     * @param string $name The id of the field.
     * @param string $error The error message.
     */
    function addField($name, $error) {
        $this->errors[] = array (
            'id' => $name,
            'msg' => $error
        );
    }

    /**
     * Return the fields added as errors.
     *
     * @return array An array of errors for specific fields.
     */
    function getFields() {
        $f = array ();
        $errors = array_values(array_filter($this->errors, array($this, 'isFieldError')));
        foreach ($errors as $fi) {
            $f[] = $fi['msg'];
        }
        return $f;
    }

    /**
     * Add an error to the error queue.
     * 
     * @param string|array $msg An error message string or custom error array.
     */
    function addError($msg) {
        $this->errors[] = $msg;
    }

    /**
     * Return all of the errors in the error queue.
     */
    function getErrors($includeFields = false) {
        $errors = $this->errors;
        if (!$includeFields) {
            $errors = array_values(array_filter($this->errors, array($this, 'isNotFieldError')));
        }
        return $errors;
    }

    /**
     * Returns true if the error passed to it represents a field error.
     * 
     * @param mixed $error An element of modError::errors.
     * @return boolean True if the error is a field error.
     */
    function isFieldError($error) {
        return (is_array($error) && isset($error['msg']) && isset($error['id']) && count($error) == 2);
    }

    /**
     * Returns true if the error passed to it does not represent a field error.
     * 
     * @param mixed $error An element of modError::errors.
     * @return boolean True if the error is not a field error.
     */
    function isNotFieldError($error) {
        return (!is_array($error) || !(isset($error['msg']) && isset($error['id']) && count($error) == 2));
    }

    /**
     * Check to see if there is any errors on the queue.
     *
     * @return boolean True if there are errors or a message has been specified.
     */
    function hasError() {
        return count($this->errors) > 0 || $this->message != '';
    }

    /**
     * Send a failure error message.
     *
     * @param string $message The error message to send.
     * @param object|array|string $object An object to send back to the output.
     */
    function failure($message = '', $object = null) {
        return $this->process($message, false, $object);
    }

    /**
     * Send a success error message.
     *
     * @param string $message The error message to send.
     * @param object|array|string $object An object to send back to the output.
     */
    function success($message = '', $object = null) {
        return $this->process($message, true, $object);
    }

    /**
     * Converts an object or objects embedded in an array, to arrays.
     *
     * This function also makes sure that any members of the array are not PHP
     * resource types (e.g. database connections, file handles, etc.).
     *
     * @param array|object $object An array or object to process.
     * @return array Returns an array representation of the object(s).
     */
    function toArray($object) {
        $array = array ();
        if (is_array($object)) {
            while (list ($key, $value) = each($object)) {
                if (!is_resource($value)) {
                    if (is_object($value) || is_array($value)) {
                        $array[$key] = $this->toArray($value);
                    } else {
                        $array[$key] = $value;
                    }
                }
            }
        }
        elseif (is_object($object)) {
            if (is_a($object, 'xPDOObject')) {
                $array = $this->toArray($object->toArray());
            } else {
                $array = $this->toArray(get_object_vars($object));
            }
        }
        if ($this->modx->getDebug() === true)
            $this->modx->log(XPDO_LOG_LEVEL_DEBUG, "modError::toArray() -- " . print_r($array, true));
        return $array;
    }
}
?>