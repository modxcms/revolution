<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Encapsulates a MODX response to a web request.
 *
 * Includes functions to manipluate header data, such as status codes, as well
 * as manipulating the response body.
 *
 * @package modx
 */
class modResponse {
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx= null;
    /**
     * The HTTP header for this Response
     * @var string $header
     */
    public $header= null;
    /**
     * The body of this response
     * @var string $body
     */
    public $body= null;
    /**
     * The current content type on the resource
     * @var modContentType $contentType
     */
    public $contentType = null;

    /**
     * @param modX $modx A reference to the modX instance
     */
    function __construct(modX &$modx) {
        $this->modx= & $modx;
    }

    /**
     * Prepare the final response after the resource has been processed.
     *
     * @param array $options Various options that can be set.
     */
    public function outputContent(array $options = array()) {
        if (!($this->contentType = $this->modx->resource->getOne('ContentType'))) {
            if ($this->modx->getDebug() === true) {
                $this->modx->log(modX::LOG_LEVEL_DEBUG, "No valid content type for RESOURCE: " . print_r($this->modx->resource->toArray(), true));
            }
            $this->modx->log(modX::LOG_LEVEL_FATAL, "The requested resource has no valid content type specified.");
        }

        if (!$this->contentType->get('binary')) {
            $this->modx->resource->prepare();

            /*FIXME: only do this for HTML content ?*/
            if (strpos($this->contentType->get('mime_type'), 'text/html') !== false) {
                $this->modx->invokeEvent('OnBeforeRegisterClientScripts');
                /* Insert Startup jscripts & CSS scripts into template - template must have a </head> tag */
                if (($js= $this->modx->getRegisteredClientStartupScripts()) && (strpos($this->modx->resource->_output, '</head>') !== false)) {
                    /* change to just before closing </head> */
                    $this->modx->resource->_output= preg_replace("/(<\/head>)/i", $js . "\n\\1", $this->modx->resource->_output,1);
                }

                /* Insert jscripts & html block into template - template must have a </body> tag */
                if ((strpos($this->modx->resource->_output, '</body>') !== false) && ($js= $this->modx->getRegisteredClientScripts())) {
                    $this->modx->resource->_output= preg_replace("/(<\/body>)/i", $js . "\n\\1", $this->modx->resource->_output,1);
                }
            }

            $this->modx->beforeRender();

            /* invoke OnWebPagePrerender event */
            if (!isset($options['noEvent']) || empty($options['noEvent'])) {
                $this->modx->invokeEvent('OnWebPagePrerender');
            }

            $totalTime= (microtime(true) - $this->modx->startTime);
            $queryTime= $this->modx->queryTime;
            $queries= isset ($this->modx->executedQueries) ? $this->modx->executedQueries : 0;
            $phpTime= $totalTime - $queryTime;
            $queryTime= sprintf("%2.4f s", $queryTime);
            $totalTime= sprintf("%2.4f s", $totalTime);
            $phpTime= sprintf("%2.4f s", $phpTime);
            $source= $this->modx->resourceGenerated ? "database" : "cache";
            $memory = number_format(memory_get_usage(true) / 1024, 0,","," ") . ' kb';
            $this->modx->resource->_output= str_replace("[^q^]", $queries, $this->modx->resource->_output);
            $this->modx->resource->_output= str_replace("[^qt^]", $queryTime, $this->modx->resource->_output);
            $this->modx->resource->_output= str_replace("[^p^]", $phpTime, $this->modx->resource->_output);
            $this->modx->resource->_output= str_replace("[^t^]", $totalTime, $this->modx->resource->_output);
            $this->modx->resource->_output= str_replace("[^s^]", $source, $this->modx->resource->_output);
            $this->modx->resource->_output= str_replace("[^m^]", $memory, $this->modx->resource->_output);
        } else {
            $this->modx->beforeRender();

            /* invoke OnWebPagePrerender event */
            if (!isset($options['noEvent']) || empty($options['noEvent'])) {
                $this->modx->invokeEvent("OnWebPagePrerender");
            }
        }

        /* send out content-type, content-disposition, and custom headers from the content type */
        if ($this->modx->getOption('set_header')) {
            $type= $this->contentType->get('mime_type') ? $this->contentType->get('mime_type') : 'text/html';
            $header= 'Content-Type: ' . $type;
            if (!$this->contentType->get('binary')) {
                $charset= $this->modx->getOption('modx_charset',null,'UTF-8');
                $header .= '; charset=' . $charset;
            }
            header($header);
            if (!$this->checkPreview()) {
                $dispositionSet= false;
                if ($customHeaders= $this->contentType->get('headers')) {
                    foreach ($customHeaders as $headerKey => $headerString) {
                        header($headerString);
                        if (strpos($headerString, 'Content-Disposition:') !== false) $dispositionSet= true;
                    }
                }
                if (!$dispositionSet && $this->modx->resource->get('content_dispo')) {
                    if ($alias= $this->modx->resource->get('uri')) {
                        $name= basename($alias);
                    } elseif ($this->modx->resource->get('alias')) {
                        $name= $this->modx->resource->get('alias');
                        if ($ext= $this->contentType->getExtension()) {
                            $name .= "{$ext}";
                        }
                    } elseif ($name= $this->modx->resource->get('pagetitle')) {
                        $name= $this->modx->resource->cleanAlias($name);
                        if ($ext= $this->contentType->getExtension()) {
                            $name .= "{$ext}";
                        }
                    } else {
                        $name= 'download';
                        if ($ext= $this->contentType->getExtension()) {
                            $name .= "{$ext}";
                        }
                    }
                    $header= 'Cache-Control: public';
                    header($header);
                    $header= 'Content-Disposition: attachment; filename=' . $name;
                    header($header);
                    $header= 'Vary: User-Agent';
                    header($header);
                }
            }
        }

        /* tell PHP to call _postProcess after returning the response (for caching) */
        register_shutdown_function(array (
            & $this->modx,
            "_postProcess"
        ));

        if ($this->modx->resource instanceof modStaticResource && $this->contentType->get('binary')) {
            $this->modx->resource->process();
        } else {
            if ($this->contentType->get('binary')) {
                $this->modx->resource->_output = $this->modx->resource->process();
            }
            @session_write_close();
            echo $this->modx->resource->_output;
            while (ob_get_level() && @ob_end_flush()) {}
            flush();
            exit();
        }
    }

    /**
     * Sends a redirect to the specified URL using the specified method.
     *
     * Valid $type values include:
     *    REDIRECT_REFRESH  Uses the header refresh method
     *    REDIRECT_META  Sends a a META HTTP-EQUIV="Refresh" tag to the output
     *    REDIRECT_HEADER  Uses the header location method
     *
     * REDIRECT_HEADER is the default.
     *
     * @param string $url The URL to redirect the client browser to.
     * @param array|boolean $options An array of options for the redirect OR
     * indicates if redirect attempts should be counted and limited to 3 (latter is deprecated
     * usage; use count_attempts in options array).
     * @param string $type The type of redirection to attempt (deprecated, use type in
     * options array).
     * @param string $responseCode The type of HTTP response code HEADER to send for the
     * redirect (deprecated, use responseCode in options array)
     * @return void|boolean
     */
    public function sendRedirect($url, $options= false, $type= '', $responseCode= '') {
        if (!is_array($options)) {
            $options = array('count_attempts' => (boolean) $options);
        }

        if ($type) {
            $this->modx->deprecated('2.0.5', 'Use type in options array instead.', 'sendRedirect method parameter $type');
        }
        if ($responseCode) {
            $this->modx->deprecated('2.0.5', 'Use responseCode in options array instead.', 'sendRedirect method parameter $responseCode');
        }
        $options = array_merge(array('count_attempts' => false, 'type' => $type, 'responseCode' => $responseCode), $options);
        $url= str_replace('&amp;','&',$url);
        if (empty ($url)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Attempted to redirect to an empty URL.");
            return false;
        }
        if (!$this->modx->getRequest()) {
            $this->modx->log(modX::LOG_LEVEL_FATAL, "Could not load request class.");
        }
        if (isset($options['preserve_request']) && !empty($options['preserve_request'])) {
            $this->modx->request->preserveRequest('referrer.redirected');
        }
        if ($options['count_attempts']) {
            /* append the redirect count string to the url */
            $currentNumberOfRedirects= isset ($_REQUEST['err']) ? $_REQUEST['err'] : 0;
            if ($currentNumberOfRedirects > 3) {
                $this->modx->log(modX::LOG_LEVEL_FATAL, 'Redirection attempt failed - please ensure the resource you\'re trying to redirect to exists. <p>Redirection URL: <i>' . $url . '</i></p>');
            } else {
                $currentNumberOfRedirects += 1;
                if (strpos($url, "?") > 0) {
                    $url .= "&err=$currentNumberOfRedirects";
                } else {
                    $url .= "?err=$currentNumberOfRedirects";
                }
            }
        }
        switch ($options['type']) {
            case 'REDIRECT_REFRESH':
                $header= 'Refresh: 0;URL=' . $url;
                break;
            case 'REDIRECT_META':
                $header= '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=' . $url . '" />';
                echo $header;
                exit();
            default:
                if (strpos($url, '://') === false && !(substr($url, 0, 1) === '/' || substr($url, 0, 2) === './' || substr($url, 0, 3) === '../')) {
                    $url= $this->modx->getOption('site_url',null,'/') . $url;
                }
                $header= 'Location: ' . $url;
                break;
        }
        @session_write_close();
        if (!empty($options['responseCode']) && (strpos($options['responseCode'], '30') !== false)) {
            header($options['responseCode']);
        }
        header($header);
        exit();
    }

    /**
     * Checks to see if the preview parameter is set.
     *
     * @return boolean
     */
    public function checkPreview() {
        $preview= false;
        if ($this->modx->checkSession('mgr') === true) {
            if (isset ($_REQUEST['z']) && $_REQUEST['z'] == 'manprev') {
                $preview= true;
            }
        }
        return $preview;
    }
}
