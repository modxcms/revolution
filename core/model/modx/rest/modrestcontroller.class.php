<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
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
     * The following options are used if the default get/put/post/delete methods are not overridden. They automate
     * the display and manipulation of data based on the classKey that is specified on the controller class, allowing
     * for quick and easy controller creation based on standard CRUD concepts.
     */

    /** @var string $classKey The xPDO class to use */
    public $classKey;
    /** @var string $classAlias The alias of the class when used in the getList method */
    public $classAlias;
    /** @var string $defaultSortField The default field to sort by in the getList method */
    public $defaultSortField = 'name';
    /** @var string $defaultSortDirection The default direction to sort in the getList method */
    public $defaultSortDirection = 'ASC';
    /** @var int $defaultLimit The default number of records to return in the getList method */
    public $defaultLimit = 20;
    /** @var int $defaultOffset The default offset in the getList method */
    public $defaultOffset = 0;
    /** @var xPDOObject $object */
    public $object;
    /** @var array $searchFields Optional. An array of fields to use when the search parameter is passed */
    public $searchFields = array();

    /** @var array $postRequiredFields An array of required field keys that must be passed for POST requests */
    public $postRequiredFields = array();
    /** @var array $postRequiredRelatedObjects An array of classKey/field pairings for checking related objects on POST */
    public $postRequiredRelatedObjects = array();
    /** @var string $postMethod The method on the object to call for POST requests */
    public $postMethod = 'save';
    /** @var array $putRequiredFields An array of required field keys that must be passed for PUT requests */
    public $putRequiredFields = array();
    /** @var array $postRequiredRelatedObjects An array of classKey/field pairings for checking related objects on PUT */
    public $putRequiredRelatedObjects = array();
    /** @var string $putMethod The method on the object to call for PUT requests */
    public $putMethod = 'save';
    /** @var array $deleteRequiredFields An array of required field keys that must be passed for DELETE requests */
    public $deleteRequiredFields = array();
    /** @var string $deleteMethod The method on the object to call for DELETE requests */
    public $deleteMethod = 'remove';
    /** @var array $allowedMethods An array of allowed request methods */
    public $allowedMethods = array('GET', 'POST', 'PUT', 'DELETE');
    /** @var array $allowedMethods An array of allowed request methods */
    public $allowedHeaders = array('Content-Type');

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
     * Check for any empty fields
     *
     * @param array $fields
     * @param boolean $setFieldError
     * @return bool|string
     */
	public function checkRequiredFields(array $fields = array(),$setFieldError = true) {
	    $missing = array();
	    foreach ($fields as $field) {
            $value = $this->getProperty($field);

            $isEmptyString = empty($value) && $value !== "0";
            $isNotBase = !is_int($value) && !is_bool($value);

	        if ($isEmptyString && $isNotBase) {
	            $missing[] = $field;
	            if ($setFieldError) {
                    $this->addFieldError($field,$this->modx->lexicon('rest.err_field_required'));
                }
	        }
	    }
	    if (!empty($missing)) {
	        return $this->modx->lexicon('rest.err_fields_required',array(
	            'fields' => implode(', ',$missing),
            ));
	    }
	    return true;
	}

    /**
     * Check to ensure the existence of required related objects on the passed request
     *
     * @param array $pairs An array of arrays in the format: 'field' => 'classKey'
     * @return boolean
     */
    public function checkRequiredRelatedObjects(array $pairs = array()) {
        $passed = true;
        foreach ($pairs as $field => $classKey) {
            if (!empty($classKey) && !empty($field)) {
                $relatedObject = $this->modx->getObject($classKey,$this->getProperty($field));
                if (empty($relatedObject)) {
                    $objectName = substr($classKey,2);
                    $this->addFieldError($field,$this->modx->lexicon('err.obj_nf',array('name' => $objectName)));
                    $passed = false;
                }
            }
        }
        return $passed;
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
            $this->getOption('responseSuccessKey','success') => $success,
            $this->getOption('responseMessageKey','message') => $message,
            $this->getOption('responseObjectKey','object') => is_object($object) ? $object->toArray() : $object,
            'code' => $status
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
     * Set a default value for a property on this controller request.
     * @param string $k The key of the field
     * @param mixed $v The default value to set
     * @param bool $useNotEmpty Whether or not to use empty() for checking set status
     * @return boolean True if the default was used
     */
    public function setDefault($k,$v,$useNotEmpty = false) {
        $isSet = false;
        if ($useNotEmpty) {
            if (!empty($this->properties[$k])) $isSet = true;
        } else if (array_key_exists($k,$this->properties)) {
            $isSet = true;
        }
        if (!$isSet) {
            $this->properties[$k] = $v;
        }
        return !$isSet;
    }

    /**
     * Set an array of default values for properties on this controller request
     * @param array $array
     * @param bool $useNotEmpty
     */
    public function setDefaults(array $array,$useNotEmpty = false) {
        foreach ($array as $k => $v) {
            $this->setDefault($k,$v,$useNotEmpty);
        }
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
     * controller. Handles fetching of collections of objects.
     *
     * @abstract
     * @return array
     */
    public function getList() {
        $this->getProperties();
        $c = $this->modx->newQuery($this->classKey);
        $c = $this->addSearchQuery($c);
        $c = $this->prepareListQueryBeforeCount($c);
        $total = $this->modx->getCount($this->classKey,$c);
        $alias = !empty($this->classAlias) ? $this->classAlias : $this->classKey;
        $c->select($this->modx->getSelectColumns($this->classKey,$alias));

        $c = $this->prepareListQueryAfterCount($c);

        $c->sortby($this->getProperty($this->getOption('propertySort','sort'),$this->defaultSortField),$this->getProperty($this->getOption('propertySortDir','dir'),$this->defaultSortDirection));
        $limit = $this->getProperty($this->getOption('propertyLimit','limit'),$this->defaultLimit);
        if (empty($limit)) $limit = $this->defaultLimit;
        $c->limit($limit,$this->getProperty($this->getOption('propertyOffset','start'),$this->defaultOffset));
        $objects = $this->modx->getCollection($this->classKey,$c);
        if (empty($objects)) $objects = array();
        $list = array();
        /** @var xPDOObject $object */
        foreach ($objects as $object) {
            $list[] = $this->prepareListObject($object);
        }
        return $this->collection($list,$total);
    }

    /**
     * Add a search query to listing calls
     *
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    protected function addSearchQuery(xPDOQuery $c) {
        $search = $this->getProperty($this->getOption('propertySearch','search'),false);
        if (!empty($search) && !empty($this->searchFields)) {
            $searchQuery = array();
            $i = 0;
            foreach ($this->searchFields as $searchField) {
                $or = $i > 0 ? 'OR:' : '';
                $searchQuery[$or.$searchField.':LIKE'] = '%'.$search.'%';
                $i++;
            }
            if (!empty($searchQuery)) {
                $c->where($searchQuery);
            }
        }
        return $c;
    }

    /**
     * Allows manipulation of the query object before the COUNT statement is called on listing calls. Override to
     * provide custom functionality.
     *
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    protected function prepareListQueryBeforeCount(xPDOQuery $c) {
        return $c;
    }

    /**
     * Allows manipulation of the query object after the COUNT statement is called on listing calls. Override to
     * provide custom functionality.
     *
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    protected function prepareListQueryAfterCount(xPDOQuery $c) {
        return $c;
    }


    /**
     * Returns an array of field-value pairs for the object when listing. Override to provide custom functionality.
     *
     * @param xPDOObject $object The current iterated object
     * @return array An array of field-value pairs of data
     */
    protected function prepareListObject(xPDOObject $object) {
        return $object->toArray();
    }

    /**
     * Get the criteria for the getObject call for GET/PUT/DELETE requests
     * @param mixed $id
     * @return array
     */
    public function getPrimaryKeyCriteria($id) {
        return array($this->primaryKeyField => $id);
    }

    /**
     * Abstract method for routing GET requests with a primary key passed. Must be defined in your derivative
     * controller.
     * @abstract
     * @param $id
     */
    public function read($id) {
        if (empty($id)) {
            return $this->failure($this->modx->lexicon('rest.err_field_ns',array(
                'field' => $this->primaryKeyField,
            )));
        }
        /** @var xPDOObject $object */
        $c = $this->getPrimaryKeyCriteria($id);
        $this->object = $this->modx->getObject($this->classKey,$c);
        if (empty($this->object)) {
            return $this->failure($this->modx->lexicon('rest.err_obj_nf',array(
                'class_key' => $this->classKey,
            )));
        }
        $objectArray = $this->object->toArray();

        $afterRead = $this->afterRead($objectArray);
        if ($afterRead !== true && $afterRead !== null) {
            return $this->failure($afterRead === false ? $this->errorMessage : $afterRead);
        }

        return $this->success('',$objectArray);
    }
    /**
     * Fires after reading the object. Override to provide custom functionality.
     *
     * @param array $objectArray A reference to the outputting array
     * @return boolean|string Either return true/false or a string message
     */
    public function afterRead(array &$objectArray) {
        return !$this->hasErrors();
    }
    /**
     * Method for routing POST requests. Can be overridden; default behavior automates xPDOObject, class-based requests.
     * @abstract
     */
    public function post() {
        $properties = $this->getProperties();

        if (!empty($this->postRequiredFields)) {
            if (!$this->checkRequiredFields($this->postRequiredFields)) {
                return $this->failure($this->modx->lexicon('error'));
            }
        }

        if (!empty($this->postRequiredRelatedObjects)) {
            if (!$this->checkRequiredRelatedObjects($this->postRequiredRelatedObjects)) {
                return $this->failure();
            }
        }

        /** @var xPDOObject $object */
        $this->object = $this->modx->newObject($this->classKey);
        $this->object->fromArray($properties);
        $beforePost = $this->beforePost();
        if ($beforePost !== true && $beforePost !== null) {
            return $this->failure($beforePost === false ? $this->errorMessage : $beforePost);
        }
        if (!$this->object->{$this->postMethod}()) {
            $this->setObjectErrors();
            if ($this->hasErrors()) {
                return $this->failure();
            } else {
                return $this->failure($this->modx->lexicon('rest.err_class_save',array(
                    'class_key' => $this->classKey,
                )));
            }

        }
        $objectArray = $this->object->toArray();
        $this->afterPost($objectArray);
        return $this->success('',$objectArray);
    }
    /**
     * Fires before saving the new object. Override to provide custom functionality.
     * @return boolean
     */
    public function beforePost() {
        return !$this->hasErrors();
    }
    /**
     * Fires after saving the new object. Override to provide custom functionality.
     *
     * @param array $objectArray A reference to the outputting array
     */
    public function afterPost(array &$objectArray) {}

    /**
     * Handles updating of objects
     * @return array
     */
    public function put() {
        $id = $this->getProperty($this->primaryKeyField,false);
        if (empty($id)) {
            return $this->failure($this->modx->lexicon('rest.err_field_ns',array(
                'field' => $this->primaryKeyField,
            )));
        }
        $c = $this->getPrimaryKeyCriteria($id);
        $this->object = $this->modx->getObject($this->classKey,$c);
        if (empty($this->object)) {
            return $this->failure($this->modx->lexicon('rest.err_obj_nf',array(
                'class_key' => $this->classKey,
            )));
        }

        if (!empty($this->putRequiredFields)) {
            if (!$this->checkRequiredFields($this->putRequiredFields)) {
                return $this->failure();
            }
        }

        if (!empty($this->putRequiredRelatedObjects)) {
            if (!$this->checkRequiredRelatedObjects($this->putRequiredRelatedObjects)) {
                return $this->failure();
            }
        }

        $this->object->fromArray($this->getProperties());

        $beforePut = $this->beforePut();
        if ($beforePut !== true && $beforePut !== null) {
            return $this->failure($beforePut === false ? $this->errorMessage : $beforePut);
        }
        if (!$this->object->{$this->putMethod}()) {
            $this->setObjectErrors();
            if ($this->hasErrors()) {
                return $this->failure();
            } else {
                return $this->failure($this->modx->lexicon('rest.err_class_save',array(
                    'class_key' => $this->classKey,
                )));
            }
        }

        $objectArray = $this->object->toArray();
        $this->afterPut($objectArray);

        return $this->success('',$objectArray);
    }
    /**
     * Fires before saving an existing object. Override to provide custom functionality.
     * @return boolean
     */
    public function beforePut() {
        return !$this->hasErrors();
    }
    /**
     * Fires after saving an existing object. Override to provide custom functionality.
     * @param array $objectArray A reference to the outputting array
     */
    public function afterPut(array &$objectArray) {}

    /**
     * Handle DELETE requests
     * @return array
     */
    public function delete() {
        $id = $this->getProperty($this->primaryKeyField,false);
        if (empty($id)) {
            return $this->failure($this->modx->lexicon('rest.err_field_ns',array(
                'field' => $this->primaryKeyField,
            )));
        }
        $c = $this->getPrimaryKeyCriteria($id);
        $this->object = $this->modx->getObject($this->classKey,$c);
        if (empty($this->object)) {
            return $this->failure($this->modx->lexicon('rest.err_obj_nf',array(
                'class_key' => $this->classKey,
            )));
        }

        if (!empty($this->deleteRequiredFields)) {
            if (!$this->checkRequiredFields($this->deleteRequiredFields)) {
                return $this->failure();
            }
        }

        $this->object->fromArray($this->getProperties());

        $beforeDelete = $this->beforeDelete();
        if ($beforeDelete !== true) {
            return $this->failure($beforeDelete === false ? $this->errorMessage : $beforeDelete);
        }
        if (!$this->object->{$this->deleteMethod}()) {
            $this->setObjectErrors();
            return $this->failure($this->modx->lexicon('rest.err_class_remove',array(
                'class_key' => $this->classKey,
            )));
        }

        $objectArray = $this->object->toArray();
        $this->afterDelete($objectArray);

        return $this->success('',$objectArray);
    }
    /**
     * Fires before deleting an existing object. Override to provide custom functionality.
     * @return boolean
     */
    public function beforeDelete() {
        return !$this->hasErrors();
    }
    /**
     * Fires after deleting an existing object. Override to provide custom functionality.
     *
     * @param array $objectArray
     */
    public function afterDelete(array &$objectArray) {}

    /**
     * Handle OPTIONS requests
     * @return array
     */
    public function options() {
        header('Access-Control-Allow-Methods: ' . implode(', ', $this->allowedMethods));
        header('Access-Control-Allow-Headers: ' . implode(', ', $this->allowedHeaders));
        $this->responseStatus = 200;
    }

    /**
     * Set object-specific model-layer errors for classes that implement the getErrors/addFieldError methods
     */
    public function setObjectErrors() {
        if (method_exists($this->object,'getErrors')) {
            $errors = $this->object->getErrors();
            foreach ($errors as $k => $msg) {
                $this->addFieldError($k,$msg);
            }
        }
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
