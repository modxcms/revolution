<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors;

use MODX\Revolution\modX;

/**
 * Response class for Processor executions
 *
 * @package MODX\Revolution
 */
class ProcessorResponse
{
    /**
     * When there is only a general error
     *
     * @const ERROR_GENERAL
     */
    const ERROR_GENERAL = 'error_general';
    /**
     * When there are only field-specific errors
     *
     * @const ERROR_FIELD
     */
    const ERROR_FIELD = 'error_field';
    /**
     * When there is both field-specific and general errors
     *
     * @const ERROR_BOTH
     */
    const ERROR_BOTH = 'error_both';
    /**
     * The field for the error type
     *
     * @const ERROR_TYPE
     */
    const ERROR_TYPE = 'error_type';

    /**
     * @var modX A reference to the modX object
     */
    public $modx = null;
    /**
     * @var array|string A reference to the full response
     */
    public $response = null;
    /**
     * @var array A collection of modProcessorResponseError objects for each field-specific error
     */
    public $errors = [];
    /**
     * @var string The error type for this response
     */
    public $error_type = '';

    /**
     * The constructor for modProcessorResponse
     *
     * @param modX  $modx     A reference to the modX object.
     * @param array $response The array response from the modX.runProcessor method
     */
    public function __construct(modX $modx, $response = [])
    {
        $this->modx =& $modx;
        $this->response = $response;
        if ($this->isError()) {
            if (!empty($response['errors']) && is_array($response['errors'])) {
                foreach ($response['errors'] as $error) {
                    $this->errors[] = new ProcessorResponseError($error);
                }
                if (!empty($response['message'])) {
                    $this->error_type = self::ERROR_BOTH;
                } else {
                    $this->error_type = self::ERROR_FIELD;
                }
            } else {
                $this->error_type = self::ERROR_GENERAL;
            }
        }
    }

    /**
     * Returns the type of error for this response
     *
     * @return string The type of error returned
     */
    public function getErrorType()
    {
        return $this->error_type;
    }

    /**
     * Checks to see if the response is an error
     *
     * @return boolean True if the response was a success, otherwise false
     */
    public function isError()
    {
        return empty($this->response) || (is_array($this->response) && (!array_key_exists('success',
                        $this->response) || empty($this->response['success'])));
    }

    /**
     * Returns true if there is a general status message for the response.
     *
     * @return boolean True if there is a general message
     */
    public function hasMessage()
    {
        return isset($this->response['message']) && !empty($this->response['message']) ? true : false;
    }

    /**
     * Gets the general status message for the response.
     *
     * @return string The status message
     */
    public function getMessage()
    {
        return isset($this->response['message']) ? $this->response['message'] : '';
    }

    /**
     * Returns the entire response object in array form
     *
     * @return array The array response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Returns true if an object was sent with this response.
     *
     * @return boolean True if an object was sent.
     */
    public function hasObject()
    {
        return isset($this->response['object']) && !empty($this->response['object']) ? true : false;
    }

    /**
     * Returns the array object, if is sent in the response
     *
     * @return array The object in the response, usually the object being performed on.
     */
    public function getObject()
    {
        return isset($this->response['object']) ? $this->response['object'] : [];
    }

    /**
     * An array of modProcessorResponseError objects for each field-specific error
     *
     * @return array
     */
    public function getFieldErrors()
    {
        return $this->errors;
    }

    /**
     * Checks to see if there are any field-specific errors in this response
     *
     * @return boolean True if there were field-specific errors
     */
    public function hasFieldErrors()
    {
        return !empty($this->errors) ? true : false;
    }

    /**
     * Gets all errors and adds them all into an array.
     *
     * @param string $fieldErrorSeparator The separator to use between fieldkey and message for field-specific errors.
     *
     * @return array An array of all errors.
     */
    public function getAllErrors($fieldErrorSeparator = ': ')
    {
        $errormsgs = [];
        if ($this->hasMessage()) {
            $errormsgs[] = $this->getMessage();
        }
        if ($this->hasFieldErrors()) {
            $errors = $this->getFieldErrors();
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    $errormsgs[] = $error->field . $fieldErrorSeparator . $error->message;
                }
            }
        }

        return $errormsgs;
    }
}
