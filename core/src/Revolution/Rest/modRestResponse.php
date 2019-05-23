<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Rest;


use Exception;
use SimpleXMLElement;
use xPDO\xPDO;

/**
 * A class for handling REST responses
 *
 * @deprecated
 *
 * @package MODX\Revolution\Rest
 */
class modRestResponse
{
    /**
     * @var string The raw response.
     */
    public $response;
    /**
     * @var string The type of response format
     */
    public $responseType = 'xml';
    /** @var SimpleXMLElement $xml */
    public $xml = null;
    /** @var array $json */
    public $json = null;

    /**
     * The constructor for the modRestResponse class.
     *
     * @param modRestClient &$client       A reference to the modRestClient instance.
     * @param string         $response     The response from the REST server.
     * @param string         $responseType The type of response, either xml or json
     *
     * @return modRestResponse
     */
    function __construct(modRestClient &$client, $response, $responseType = 'xml')
    {
        $this->client =& $client;
        $this->response = (string)$response;
        $this->responseType = $responseType;
        if ($responseType == 'xml') {
            $this->toXml();
        } elseif ($responseType == 'json') {
            $this->fromJSON();
        }
    }

    /**
     * Translates the current response object to a SimpleXMLElement instance
     *
     * @access public
     * @return SimpleXMLElement
     */
    public function toXml()
    {
        if (!empty($this->xml) && $this->xml instanceof SimpleXMLElement) {
            return $this->xml;
        }

        try {
            $this->xml = simplexml_load_string($this->response);
        } catch (Exception $e) {
            $this->client->modx->log(xPDO::LOG_LEVEL_ERROR,
                'Could not parse XML response from provider: ' . $this->response);
        }
        if (!$this->xml) {
            $this->client->modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not connect to provider at: ' . $this->client->host);
            $this->xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><error><message>' . $this->client->modx->lexicon('provider_err_blank_response') . '</message></error>');

            return $this->xml;
        }

        return $this->xml;
    }

    /**
     * Translates current response from JSON to an array
     *
     * @access public
     * @return array
     */
    public function fromJSON()
    {
        if (!empty($this->json)) {
            return $this->json;
        }

        $this->json = $this->client->modx->fromJSON($this->response);

        return $this->json;
    }

    /**
     * Checks to see whether or not the response is an error response
     *
     * @access public
     * @return boolean True if the response is an error
     */
    public function isError()
    {
        if ($this->responseType == 'xml') {
            $this->toXml();
            $isError = $this->xml->getName() == 'error';
        } elseif ($this->responseType = 'json') {
            $this->fromJSON();
            $isError = !empty($this->json['error']) ? true : false;
        } else {
            $isError = !empty($this->response);
        }

        return $isError;
    }

    /**
     * Returns an error message, if any.
     *
     * @access public
     * @return string The error message
     */
    public function getError()
    {
        $message = '';
        if ($this->responseType == 'xml') {
            if (empty($this->xml) || !($this->xml instanceof SimpleXMLElement)) {
                $message = '';
            } else {
                $message = (string)$this->xml->message;
            }
        } elseif ($this->responseType == 'json') {
            $this->fromJSON();
            $message = !empty($this->json['error']) && !empty($this->json['message']) ? $this->json['message'] : '';
        }

        return $message;
    }
}
