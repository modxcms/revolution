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


use MODX\Revolution\modX;

/**
 * REST Client service class with XML/JSON/QS support
 *
 * @package    modx
 * @subpackage rest
 */
class modRest
{
    /** @var modX $modx */
    public $modx;
    /** @var array $config */
    public $config = [];
    /** @var mixed $handle The cURL resource handle. */
    public $handle;

    /** @var object $response Response body. */
    public $response;
    /** @var object $headers Parsed response header object */
    public $headers;
    /** @var object $info Response info object */
    public $info;
    /** @var string $error Response error string. */
    public $error;
    /** @var string $url The URL to query */
    public $url;

    /**
     * The modRest constructor
     *
     * @param modX  $modx   A reference to the modX instance
     * @param array $config An array of configuration options
     */
    public function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;
        $this->config = array_merge([
            'addMethodParameter' => false,
            'baseUrl' => null,
            'curlOptions' => [],
            'defaultParameters' => [],
            'format' => null,
            'headers' => [],
            'password' => null,
            'suppressSuffix' => false,
            'userAgent' => 'MODX RestClient/1.0.0',
            'username' => null,
        ], $config);
        $this->modx->getService('lexicon', 'modLexicon');
        if ($this->modx->lexicon) {
            $this->modx->lexicon->load('rest');
        }
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setOption($key, $value)
    {
        $this->config[$key] = $value;
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getOption($key, $default = null)
    {
        return array_key_exists($key, $this->config) ? $this->config[$key] : $default;
    }

    /**
     * @param string $url
     * @param array  $parameters
     * @param array  $headers
     *
     * @return RestClientResponse
     */
    public function get($url, $parameters = [], $headers = [])
    {
        return $this->execute($url, 'GET', $parameters, $headers);
    }

    /**
     * @param string $url
     * @param array  $parameters
     * @param array  $headers
     *
     * @return RestClientResponse
     */
    public function post($url, $parameters = [], $headers = [])
    {
        return $this->execute($url, 'POST', $parameters, $headers);
    }

    /**
     * @param string $url
     * @param array  $parameters
     * @param array  $headers
     *
     * @return RestClientResponse
     */
    public function put($url, $parameters = [], $headers = [])
    {
        if (!empty($this->config['addMethodParameter'])) {
            $parameters['_method'] = "PUT";
        }

        return $this->execute($url, 'PUT', $parameters, $headers);
    }

    /**
     * @param string $url
     * @param array  $parameters
     * @param array  $headers
     *
     * @return RestClientResponse
     */
    public function delete($url, $parameters = [], $headers = [])
    {
        if (!empty($this->config['addMethodParameter'])) {
            $parameters['_method'] = "DELETE";
        }

        return $this->execute($url, 'DELETE', $parameters, $headers);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $parameters
     * @param array  $headers
     *
     * @return RestClientResponse
     */
    public function request($method, $url, $parameters = [], $headers = [])
    {
        return $this->execute($url, $method, $parameters, $headers);
    }

    /**
     * @param string $url
     * @param string $method
     * @param array  $parameters
     * @param array  $headers
     *
     * @return RestClientResponse
     */
    protected function execute($url, $method = 'GET', $parameters = [], $headers = [])
    {
        $request = new RestClientRequest($this->modx, $this->config);
        if (!empty($headers['rootNode'])) {
            $request->setRootNode($headers['rootNode']);
        }

        return $request->execute($url, $method, $parameters, $headers);
    }
}
