<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
 * validation of any kind of MODX request.
 * @package modx
 * @subpackage error
 */
class modError {
    /**
    * @var array The array of errors
    */
    public $errors;
    /**
    * @var string The error message to output.
    */
    public  $message;
    /**
    * @var modX A reference to the $modx object.
    */
    public  $modx;
    /**
    * @var integer The total number of errors.
    */
    public  $total = 0;
    /**
     * @var boolean Indicates failure or success.
     */
    public  $status = false;
    /**
     * @var array An array of objects to validate against
     */
    protected $_objects = array();

    /**
     * @param modX $modx A reference to the modX instance
     * @param string $message The default message to send as an error response
     */
    function __construct(modX &$modx, $message = '') {
        $this->modx =& $modx;
        if (is_string($message)) {
            $this->message = $message;
        } elseif (is_array($message) && isset($message['message'])) {
            $this->message = $message['message'];
        } else {
            $this->message = '';
        }
        $this->errors = array ();
    }

    /**
     * Adds an object to the validation queue.
     *
     * @access public
     * @param xPDOObject $obj An xPDOObject to validate.
     */
    public function addObjectToValidate(xPDOObject &$obj) {
        if (is_object($obj) && $obj instanceof xPDOObject) {
            $this->_objects[]= $obj;
        }
    }

    /**
     * Checks validation, and if any errors are found, returns them. Error
     * handlers that derive from this can determine their own behaviour should
     * errors be found.
     * @access public
     * @param xPDOObject|array $objs An xPDOObject or array of xPDOObjects to
     * add to the validation queue.
     * @return string The validation message returned.
     */
    public function checkValidation($objs= array()) {
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
     * @access protected
     * @return string The compiled validation message returned.
     */
    protected function _validate() {
        $s = '';
        /** @var xPDOObject $obj */
        foreach ($this->_objects as $obj) {
            /** @var modValidator $validator */
            if ($validator= $obj->getValidator()) {
                $messages= $validator->getMessages();
                if (!empty($messages)) {
                    $fields = array();
                    foreach ($messages as $message) {
                        $s .= $message['message'].'<br />'."\n";
                        if (!isset($fields[$message['field']])) $fields[$message['field']] = array();
                        $fields[$message['field']][$message['name']] = $message['message'];
                    }
                    foreach ($fields as $fieldKey => $field) {
                        foreach ($field as $fieldMsgName => $fieldMsg) {
                            $this->addField($fieldKey, $fieldMsg);
                        }
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
    public function process($message = '', $status = false, $object = null) {
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
            if (is_object($obj) && $obj instanceof xPDOObject) {
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
    public function addField($name, $error) {
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
    public function getFields() {
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
    public function addError($msg) {
        $this->errors[] = $msg;
    }

    /**
     * Return all of the errors in the error queue.
     *
     * @param boolean $includeFields Whether or not to include the fields in the error response
     * @return array An array of errors
     */
    public function getErrors($includeFields = false) {
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
    public function isFieldError($error) {
        return (is_array($error) && isset($error['msg']) && isset($error['id']) && count($error) == 2);
    }

    /**
     * Returns true if the error passed to it does not represent a field error.
     *
     * @param mixed $error An element of modError::errors.
     * @return boolean True if the error is not a field error.
     */
    public function isNotFieldError($error) {
        return (!is_array($error) || !(isset($error['msg']) && isset($error['id']) && count($error) == 2));
    }

    /**
     * Check to see if there is any errors on the queue.
     *
     * @return boolean True if there are errors or a message has been specified.
     */
    public function hasError() {
        return count($this->errors) > 0 || $this->message != '';
    }

    /**
     * Send a failure error message.
     *
     * @param string $message The error message to send.
     * @param object|array|string $object An object to send back to the output.
     * @return string|array The failure response
     */
    public function failure($message = '', $object = null) {
        return $this->process($message, false, $object);
    }

    /**
     * Send a success error message.
     *
     * @param string $message The error message to send.
     * @param object|array|string $object An object to send back to the output.
     * @return string|array The success response
     */
    public function success($message = '', $object = null) {
        return $this->process($message, true, $object);
    }

    /**
     * Converts an object or objects embedded in an array, to arrays.
     *
     * This function also makes sure that any members of the array are not PHP
     * resource types (e.g. database connections, file handles, etc.).
     *
     * @param array|xPDOObject|object $object An array or object to process.
     * @return array Returns an array representation of the object(s).
     */
    public function toArray($object) {
        $array = array ();
        if (is_array($object)) {
            foreach ($object as $key => $value) {
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
            if ($object instanceof xPDOObject) {
                $array = $this->toArray($object->toArray());
            } else {
                $array = $this->toArray(get_object_vars($object));
            }
        }
        if ($this->modx->getDebug() === true)
            $this->modx->log(xPDO::LOG_LEVEL_DEBUG, "modError::toArray() -- " . print_r($array, true));
        return $array;
    }

    /**
     * Resets the error messages.
     */
    public function reset() {
        $this->errors = array();
        $this->message = '';
        $this->total = 0;
        $this->status = true;
    }
}
