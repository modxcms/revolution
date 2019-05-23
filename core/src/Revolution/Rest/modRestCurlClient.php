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
 * Handles REST requests through a cURL-based client
 *
 * @deprecated To be removed in 2.3. See modRest instead.
 *
 * @package    MODX\Revolution\Rest
 */
class modRestCurlClient extends modRestClient
{
    /**
     * @param modX  $modx   A reference to the modX object
     * @param array $config An array of configuration options
     */
    function __construct(modX &$modx, array $config = [])
    {
        parent::__construct($modx, $config);
        $this->config = array_merge([
        ], $this->config);
    }

    /**
     * Extends modRestClient::request to provide cURL specific request handling
     *
     * @param string $host    The host of the REST server.
     * @param string $path    The path to request to on the REST server.
     * @param string $method  The HTTP method to use for the request. May be GET,
     *                        PUT or POST.
     * @param array  $params  An array of parameters to send with the request.
     * @param array  $options An array of options to pass to the request.
     *
     * @return modRestResponse The response object.
     */
    public function request($host, $path, $method = 'GET', array $params = [], array $options = [])
    {
        /* start our cURL connection */
        $ch = curl_init();

        /* setup request */
        $this->setUrl($ch, $host, $path, $method, $params, $options);
        $this->setAuth($ch, $options);
        $this->setProxy($ch, $options);
        $this->setOptions($ch, $options);

        /* execute request */
        $result = trim(curl_exec($ch));

        /* make sure to close connection */
        curl_close($ch);

        return $result;
    }

    /**
     * Configure and set the URL to use, along with any request parameters.
     *
     * @param resource $ch      The cURL connection resource
     * @param string   $host    The host to send the request to
     * @param string   $path    The path of the request
     * @param string   $method  The method of the request (GET/POST)
     * @param array    $params  An array of request parameters to attach to the URL
     * @param array    $options An array of options when setting the URL
     *
     * @return boolean Whether or not the URL was set
     * @see modRestClient::request for parameter documentation.
     */
    public function setUrl($ch, $host, $path, $method = 'GET', array $params = [], array $options = [])
    {
        $q = http_build_query($params);
        switch ($method) {
            case 'GET':
                $path .= (strpos($host, '?') === false ? '?' : '&') . $q;
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, 1);
                $contentType = $this->modx->getOption('contentType', $options, 'xml');
                switch ($contentType) {
                    case 'json':
                        $json = $this->modx->toJSON($params);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                        break;
                    case 'xml':
                        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/xml']);
                        $xml = modRestArrayToXML::toXML($params,
                            !empty($options['rootNode']) ? $options['rootNode'] : 'request');
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
                        break;
                    case 'string':
                        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
                        $string = implode('&', array_map(create_function('$v, $k', 'return $k . "=" . $v;'), $params,
                            array_keys($params)));
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
                        break;
                    default:
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                        break;
                }
                break;
        }
        /* prevent invalid xhtml ampersands in request path and strip unnecessary ampersands from the end of the url */
        $url = rtrim(str_replace('&amp;', '&', $host . $path), '&');

        return curl_setopt($ch, CURLOPT_URL, $url);
    }

    /**
     * Set up cURL-specific options
     *
     * @param resource $ch      The cURL connection resource
     * @param array    $options An array of options
     */
    public function setOptions($ch, array $options = [])
    {
        /* always return us the result */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,
            !empty($options['curlopt_returntransfer']) ? $options['curlopt_returntransfer'] : 1);
        /* we dont want header gruft */
        curl_setopt($ch, CURLOPT_HEADER, !empty($options['curlopt_header']) ? $options['curlopt_header'] : 0);
        /* change the request type to HEAD, mostly used in conjunction with curlopt_header to reduce transfer size in remote file checks */
        curl_setopt($ch, CURLOPT_NOBODY, !empty($options['curlopt_nobody']) ? $options['curlopt_nobody'] : 0);
        /* attempt to retrieve the modification date of the remote document for use with curl_getinfo() */
        curl_setopt($ch, CURLOPT_FILETIME, !empty($options['curlopt_filetime']) ? $options['curlopt_filetime'] : 0);
        /* default timeout to 30 seconds */
        curl_setopt($ch, CURLOPT_TIMEOUT,
            !empty($options['curlopt_timeout']) ? $options['curlopt_timeout'] : $this->config[modRestClient::OPT_TIMEOUT]);
        /* disable verifypeer since it's not helpful on most environments */
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,
            !empty($options['curlopt_ssl_verifypeer']) ? $options['curlopt_ssl_verifypeer'] : 0);
        /* send a useragent to allow proper responses */
        curl_setopt($ch, CURLOPT_USERAGENT,
            !empty($options['curlopt_useragent']) ? $options['curlopt_useragent'] : $this->config[modRestClient::OPT_USERAGENT]);
        /* send a custom referer if provided */
        if (!empty($options['curlopt_referer'])) {
            curl_setopt($ch, CURLOPT_REFERER, $options['curlopt_referer']);
        }
        /* handle upload options */
        if (!empty($options['curlopt_usrpwd'])) {
            curl_setopt($ch, CURLOPT_USERPWD, $options['curlopt_usrpwd']);
        }
        if (!empty($options['curlopt_upload'])) {
            curl_setopt($ch, CURLOPT_UPLOAD, $options['curlopt_upload']);
        }
        if (!empty($options['curlopt_infile'])) {
            curl_setopt($ch, CURLOPT_INFILE, $options['curlopt_infile']);
        }
        if (!empty($options['curlopt_infilesize'])) {
            curl_setopt($ch, CURLOPT_INFILESIZE, $options['curlopt_infilesize']);
        }
        if (!empty($options['curlopt_file'])) {
            curl_setopt($ch, CURLOPT_FILE, $options['curlopt_file']);
        } // directly write to file
        /* close connection, connection is not pooled to reuse */
        curl_setopt($ch, CURLOPT_FORBID_REUSE,
            !empty($options['curlopt_forbid_reuse']) ? $options['curlopt_forbid_reuse'] : 0);
        /* force the use of a new connection instead of a cached one */
        curl_setopt($ch, CURLOPT_FRESH_CONNECT,
            !empty($options['curlopt_fresh_connect']) ? $options['curlopt_fresh_connect'] : 0);

        /* can only use follow location if safe_mode and open_basedir are off */
        $safeMode = ini_get('safe_mode');
        $openBasedir = ini_get('open_basedir');
        if (empty($safeMode) && empty($openBasedir)) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION,
                !empty($options['curlopt_followlocation']) ? $options['curlopt_followlocation'] : 1);
        }
    }

    /**
     * Set up authentication configuration , if specified, to be used with REST request.
     *
     * @param resource $ch      The cURL connection resource.
     * @param array    $options An array of options
     *
     * @return boolean True if authentication was used.
     */
    public function setAuth($ch, array $options = [])
    {
        $auth = false;
        if (!empty($options[modRestClient::OPT_USERPWD])) {
            $options[modRestClient::OPT_AUTHTYPE] = $this->modx->getOption(modRestClient::OPT_AUTHTYPE, $options,
                'BASIC');
            switch ($options[modRestClient::OPT_AUTHTYPE]) {
                case 'ANY':
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                    break;
                case 'ANYSAFE':
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANYSAFE);
                    break;
                case 'DIGEST':
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
                    break;
                case 'GSSNEGOTIATE':
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_GSSNEGOTIATE);
                    break;
                case 'NTLM':
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
                    break;
                default:
                case 'BASIC':
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    break;
            }
            $auth = curl_setopt($ch, CURLOPT_USERPWD,
                !empty($options[modRestClient::OPT_USERPWD]) ? $options[modRestClient::OPT_USERPWD] : 'username:password');
        }

        return $auth;
    }

    /**
     * Set up proxy configuration , if specified, to be used with REST request.
     *
     * @param resource $ch      The cURL connection resource.
     * @param array    $options An array of options
     *
     * @return boolean True if the proxy was setup.
     */
    public function setProxy($ch, array $options = [])
    {
        $proxyEnabled = false;
        /* if proxy is set, attempt to use it */
        $proxyHost = $this->modx->getOption('proxy_host', null, '');
        if (!empty($proxyHost)) {
            $proxyEnabled = curl_setopt($ch, CURLOPT_PROXY, $proxyHost);
            $proxyPort = $this->modx->getOption('proxy_port', null, '');
            if (!empty($proxyPort)) {
                curl_setopt($ch, CURLOPT_PROXYPORT, $proxyPort);
            }
            $proxyUserpwd = $this->modx->getOption('proxy_username', null, '');
            if (!empty($proxyUserpwd)) {
                $proxyAuthType = $this->modx->getOption('proxy_auth_type', null, 'BASIC');
                $proxyAuthType = $proxyAuthType == 'NTLM' ? CURLAUTH_NTLM : CURLAUTH_BASIC;
                curl_setopt($ch, CURLOPT_PROXYAUTH, $proxyAuthType);

                $proxyPassword = $this->modx->getOption('proxy_password', null, '');
                if (!empty($proxyPassword)) {
                    $proxyUserpwd .= ':' . $proxyPassword;
                }
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyUserpwd);
            }
        }

        return $proxyEnabled;
    }
}
