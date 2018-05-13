<?php

namespace MODX\Processors;

/**
 * An abstraction class of field-specific errors for a processor response
 *
 * @package modx
 */
class modProcessorResponseError
{
    /**
     * @var array The error data itself
     */
    public $error = null;
    /**
     * @var string The field key that the error occurred on
     */
    public $field = null;
    /**
     * @var string The message that was sent for the field error
     */
    public $message = '';


    /**
     * The constructor for the modProcessorResponseError class
     *
     * @param array $error An array error response
     */
    function __construct($error = [])
    {
        $this->error = $error;
        if (isset($error['id']) && !empty($error['id'])) {
            $this->field = $error['id'];
        }
        if (isset($error['msg']) && !empty($error['msg'])) {
            $this->message = $error['msg'];
        }
    }


    /**
     * Returns the message for the field-specific error
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }


    /**
     * Returns the field key for the field-specific error
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }


    /**
     * Returns the array data for the field-specific error
     *
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }
}