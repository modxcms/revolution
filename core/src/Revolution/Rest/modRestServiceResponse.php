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


use XMLWriter;

/**
 * The REST response class for the service
 *
 * @package MODX\Revolution\Rest
 */
class modRestServiceResponse
{
    /** @var modRestService $service The modRestService instance */
    public $service;
    /** @var array $body The data body of the response */
    public $body;
    /** @var int $status The status code of the response */
    public $status;
    /** @var string $contentType The string content type of the response */
    public $contentType = 'json';
    /** @var array $payload The data payload being sent as the response */
    protected $payload = [];

    /**
     * Map of formats to their parallel content types
     *
     * @var array
     */
    protected static $contentTypes = [
        'xml' => 'application/xml',
        'json' => 'application/json',
        'qs' => 'text/plain',
    ];
    /**
     * Dictionary of response codes and their text descriptions
     *
     * @var array
     */
    protected static $responseCodes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
    ];

    /**
     * @param modRestService $service A reference to the modRestService instance
     * @param string         $body    The actual body of the response
     * @param string|int     $status  The status code for the response
     */
    function __construct(modRestService &$service, $body, $status)
    {
        $this->service = &$service;
        $this->body = $body;
        $this->status = $status;
    }

    /**
     * Set the content type for this response
     *
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * Prepare the response, properly formatting the body and generating the payload
     */
    public function prepare()
    {
        if (!empty($this->body)) {
            $this->payload = ['status' => $this->status, 'body' => $this->getFormattedBody()];
        } else {
            $this->contentType = 'qs';
            $this->payload = ['status' => $this->status, 'body' => $this->body];
        }
    }

    /**
     * Format the body based on the content type of the response
     *
     * @access protected
     * @return string
     */
    protected function getFormattedBody()
    {
        switch ($this->contentType) {
            case 'xml':
                $data = $this->toXml($this->body);
                break;
            case 'qs':
                $data = http_build_query($this->body);
                break;
            case 'json':
            case 'js':
            default:
                $data = $this->service->modx->toJSON($this->body);
                break;
        }

        return $data;
    }

    /**
     * Send the response back to the client.
     */
    public function send()
    {
        $contentType = $this->getResponseContentType($this->contentType);
        $status = !empty($this->payload['status']) ? $this->payload['status'] : 200;
        $body = empty($this->payload['body']) ? '' : $this->payload['body'];

        $headers = $_SERVER['SERVER_PROTOCOL'] . ' ' . $status . ' ' . $this->getResponseCodeMessage($status);
        header($headers);
        header('Content-Type: ' . $contentType);
        echo $body;
        if ($this->service->getOption('exitOnResponse', true)) {
            @session_write_close();
            exit(0);
        }
    }

    /**
     * Get the proper response code message for the passed status code
     *
     * @param int $status
     *
     * @return string
     */
    protected function getResponseCodeMessage($status)
    {
        return (isset(self::$responseCodes[$status])) ? self::$responseCodes[$status] : self::$responseCodes[500];
    }

    /**
     * Get the proper HTTP content type for the passed format
     *
     * @param string $format
     *
     * @return string
     */
    protected function getResponseContentType($format = 'json')
    {
        return self::$contentTypes[$format];
    }

    /**
     * Convert an array to XML output
     *
     * @param array  $data
     * @param string $version
     * @param string $encoding
     *
     * @return string
     */
    protected function toXml($data, $version = '1.0', $encoding = 'UTF-8')
    {
        $xml = new XMLWriter;
        $xml->openMemory();
        $xml->startDocument($version, $encoding);
        $xml->startElement($this->service->getOption('xmlRootNode', 'response'));
        $this->_xml($xml, $data);
        $xml->endElement();

        return $xml->outputMemory(true);
    }

    /**
     * Helper method for converting an array to XML output
     *
     * @param XMLWriter $xml
     * @param mixed     $data
     * @param string    $old_key
     */
    protected function _xml(XMLWriter $xml, $data, $old_key = null)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (!is_int($key)) {
                    $xml->startElement($key);
                } else {
                    $singleKey = trim($old_key, 's');
                    $xml->startElement($singleKey);
                }
                $this->_xml($xml, $value, $key);
                $xml->endElement();
                continue;
            }
            $key = (is_int($key)) ? $old_key . $key : $key;
            if (!is_object($value)) {
                $xml->writeElement($key, $value);
            }
        }
    }
}
