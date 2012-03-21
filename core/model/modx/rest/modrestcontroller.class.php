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
 * Abstract controller class for modRestService; all REST controllers must extend this class to be properly
 * implemented.
 *
 * @package modx
 * @subpackage rest
 */
abstract class modRestController {
    /** @var modX $modx The modX instance */
    public $modx;
    /** @var array $config An array of configuration properties, passed from modRestService */
    public $config = array();
    /** @var array $properties An array of request parameters passed */
    public $properties = array();
    /** @var array $headers An array of HTTP headers passed */
    public $headers = array();
    /** @var string $primaryKeyField The primary key field for this controller; useful when automating REST calls */
    public $primaryKeyField = 'id';
    /** @var array $errors An array of errors that may have occurred for this controller */
    public $errors = array();
    /** @var string $errorMessage A generic error message for this response */
    public $errorMessage = '';
    /** @var boolean $protected Whether or not this controller is "protected" - meaning whether or not verifyAuthentication will be called*/
    protected $protected = true;
    /** @var \modRestServiceRequest $request The request object passed to this controller */
	protected $request;
	/** @var string $response The response being sent by this controller */
	protected $response;
	/** @var string $responseStatus The response status being sent by this controller */
	protected $responseStatus;

    /**
     * @param modX $modx The modX instance
     * @param modRestServiceRequest $request The rest service request class instance
     * @param array $config An array of configuration properties, passed through from modRestService
     */
	public function __construct(modX $modx,modRestServiceRequest $request,array $config = array()) {
	    $this->modx =& $modx;
		$this->request =& $request;
		$this->config = array_merge($this->config,$config);
	}

    /**
     * Get a configuration option for this controller
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getOption($key,$default = null) {
        return array_key_exists($key,$this->config) ? $this->config[$key] : $default;
    }

    /**
     * Route GET requests
     * @return array
     */
    public function get() {
        $pk = $this->getProperty($this->primaryKeyField);
        if (empty($pk)) {
            return $this->getList();
        }
        return $this->read($pk);
    }

    /**
     * Abstract method for routing GET requests without a primary key passed. Must be defined in your derivative
     * controller.
     * @abstract
     */
    abstract public function getList();
    /**
     * Abstract method for routing GET requests with a primary key passed. Must be defined in your derivative
     * controller.
     * @abstract
     * @param $id
     */
    abstract public function read($id);
    /**
     * Abstract method for routing POST requests. Must be defined in your derivative controller.
     * @abstract
     */
	abstract public function post();
	/**
     * Abstract method for routing PUT requests. Must be defined in your derivative controller.
     * @abstract
     */
	abstract public function put();
	/**
     * Abstract method for routing DELETE requests. Must be defined in your derivative controller.
     * @abstract
     */
	abstract public function delete();

    /**
     * Initialize the controller
     */
    public function initialize() {}

    /**
     * Override to verify authentication on this specific controller. Useful for managing permissions.
     *
     * @return boolean
     */
	public function verifyAuthentication() {
	    return true;
	}

    /**
     * Return whether or not this controller is set to be protected
     * @final
     * @return bool
     */
	final public function isProtected() {
	    return $this->protected;
	}

    /**
     * Check required fields for a controller.
     *
     * @param array $fields
     * @return bool|string
     */
	public function checkRequiredFields(array $fields = array()) {
	    $missing = array();
	    foreach ($fields as $field) {
	        $value = $this->getProperty($field);
	        if (empty($value)) {
	            $missing[] = $field;
	        }
	    }
	    if (!empty($missing)) {
	        return 'The following fields are required: '.implode(', ',$missing);
	    }
	    return true;
	}

    /**
     * Get a REQUEST property for the controller
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
	public function getProperty($key,$default =null) {
	    $value = $default;
	    if (array_key_exists($key,$this->properties)) {
	        $value = $this->properties[$key];
	    }
	    return $value;
	}

    /**
     * Set a request property for the controller
     *
     * @param string $key
     * @param string $value
     */
	public function setProperty($key,$value) {
	    $this->properties[$key] = $value;
	}

    /**
     * Unset a request property for the controller
     * @param string $key
     */
	public function unsetProperty($key) {
	    unset($this->properties[$key]);
	}

    /**
     * Get the request properties for the controller
     * @return array
     */
	public function getProperties() {
	    return $this->properties;
	}

	/**
     * Set a collection of properties for the controller
     *
     * @param array $properties An array of properties
     * @param bool $merge Optionally, only merge properties in if this is true
     */
	public function setProperties(array $properties = array(),$merge = false) {
        $this->properties = $merge ? array_merge($this->properties,$properties) : $properties;
	}

    /**
     * Set the HTTP request headers for this controller
     *
     * @param array $headers An array of headers
     * @param bool $merge Optionally, only merge headers in if this is true
     */
	public function setHeaders(array $headers = array(),$merge = false) {
	    $this->headers = $merge ? array_merge($this->headers,$headers) : $headers;
	}

    /**
     * Get the request headers for this controller
     * @return array
     */
    final public function getHeaders() {
        return $this->headers;
    }

    /**
     * Return a success message for this controller, with an optional return object
     *
     * @param string $message Optional. The success response message.
     * @param array|xPDOObject $object Optional. An xPDOObject or array to send as the return object.
     * @param int $status Optional. The status code to send.
     */
    public function success($message = '',$object = array(),$status = null) {
        if (empty($status)) $status = $this->getOption('defaultSuccessStatusCode',200);
        $this->process(true,$message,$object,$status);
    }

    /**
     * Return a failure message for this controller, with an optional return object. Will also automatically
     * send errors in an errors root node if any are found.
     *
     * @param string $message Optional. The failure response message.
     * @param array|xPDOObject $object Optional. An xPDOObject or array to send as the return object.
     * @param int $status Optional. The status code to send.
     */
    public function failure($message = '',$object = array(),$status = null) {
        if (empty($status)) $status = $this->getOption('defaultFailureStatusCode',200);
        $this->process(false,$message,$object,$status);
    }

    /**
     * Process the response and format in the proper response format.
     *
     * @param bool $success Whether or not this response is successful.
     * @param string $message Optional. The response message.
     * @param array|xPDOObject $object Optional. The response return object.
     * @param int $status Optional. The response code.
     */
    protected function process($success = true,$message = '',$object = array(),$status = 200) {
        $response = array(
            $this->getOption('responseMessageKey','message') => $message,
            $this->getOption('responseObjectKey','object') => is_object($object) ? $object->toArray() : $object,
            $this->getOption('responseSuccessKey','success') => $success,
        );
        if (empty($success) && !empty($this->errors)) {
            $response[$this->getOption('responseErrorsKey','errors')] = $this->errors;
        }
        $this->modx->log(modX::LOG_LEVEL_DEBUG,'[REST] Sending REST response: '.print_r($response,true));
        $this->response = $response;
        $this->responseStatus = empty($status) ? (empty($success) ? $this->getOption('defaultFailureStatusCode',200) : $this->getOption('defaultSuccessStatusCode',200)) : $status;
    }

    /**
     * Return the response status code
     * @return string
     */
	final public function getResponseStatus() {
		return $this->responseStatus;
	}

    /**
     * Return the response payload
     *
     * @return string
     */
	final public function getResponse() {
		return $this->response;
	}

    /**
     * Get any errors that may have been set on this controller
     *
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * See if any errors have been set during the request on this controller
     *
     * @return bool
     */
    public function hasErrors() {
        return !empty($this->errors) || !empty($this->errorMessage);
    }

    /**
     * Set an error for a specific field
     * @param string $k The key of the field to set
     * @param string $v The error message to set
     * @param boolean $append Whether or not to append the error message or overwrite it
     */
    public function addFieldError($k,$v,$append = true) {
        if ($append && !empty($this->errors[$k])) {
            $separator = $this->getOption('errorMessageSeparator',' ');
            $this->errors[$k] .= $separator.$v;
        } else {
            $this->errors[$k] = $v;
        }
    }
    /**
     * Remove an error from a field
     *
     * @param string $k
     */
    public function removeFieldError($k) {
        unset($this->errors[$k]);
    }

    /**
     * Set the general error message
     *
     * @param string $message
     */
    public function setErrorMessage($message) {
        $this->errorMessage = $message;
    }

    /**
     * Clear the general error message
     */
    public function clearErrorMessage() {
        $this->errorMessage = '';
    }

    /**
     * Output a collection of objects as a list.
     *
     * @param array $list
     * @param int|boolean $total
     * @param int $status
     */
    public function collection($list = array(),$total = false,$status = null) {
        if (empty($status)) $status = $this->getOption('defaultSuccessStatusCode',200);
        if ($total === false) {
            $total = count($list);
        }
        $this->response = array(
            $this->getOption('collectionResultsKey','results') => $list,
            $this->getOption('collectionTotalKey','total') => $total,
        );
        $this->responseStatus = $status;
    }

    /**
     * If an object is set, set the object fields with the passed values
     *
     * @param object $object
     * @param array $values
     * @return mixed
     */
    public function setObjectFields(&$object,array $values = array()) {
        foreach ($values as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $object->{$key[$k]} = $v;
                }
            } else {
                $object->{$key} = $value;
            }
        }
        return $object;
    }
}
