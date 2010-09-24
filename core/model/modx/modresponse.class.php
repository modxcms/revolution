<?php
/*
 * MODx Revolution
 *
 * Copyright 2006-2010 by the MODx Team.
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
 */
/**
 * Encapsulates a MODx response to a web request.
 *
 * Includes functions to manipluate header data, such as status codes, as well
 * as manipulating the response body.
 *
 * @package modx
 */
class modResponse {
    public $modx= null;
    public $header= null;
    public $body= null;

    function __construct(modX &$modx) {
        $this->modx= & $modx;
    }

    /**
     * Prepare the final response after the resource has been processed.
     *
     * @param array $options Various options that can be set.
     */
    public function outputContent(array $options = array()) {
        if (!($contentType = $this->modx->resource->getOne('ContentType'))) {
            if ($this->modx->getDebug() === true) {
                $this->modx->log(modX::LOG_LEVEL_DEBUG, "No valid content type for RESOURCE: " . print_r($this->modx->resource->toArray(), true));
            }
            $this->modx->log(modX::LOG_LEVEL_FATAL, "The requested resource has no valid content type specified.");
        }

        if (!$contentType->get('binary')) {
            $this->modx->resource->process();

            $this->modx->resource->_output= $this->modx->resource->_content;
            $this->modx->resource->_jscripts= $this->modx->jscripts;
            $this->modx->resource->_sjscripts= $this->modx->sjscripts;
            $this->modx->resource->_loadedjscripts= $this->modx->loadedjscripts;

            /* collect any uncached element tags in the content and process them */
            $this->modx->getParser();
            $maxIterations= intval($this->modx->getOption('parser_max_iterations', $options, 10));
            $this->modx->parser->processElementTags('', $this->modx->resource->_output, true, false, '[[', ']]', array(), $maxIterations);
            $this->modx->parser->processElementTags('', $this->modx->resource->_output, true, true, '[[', ']]', array(), $maxIterations);

            /*FIXME: only do this for HTML content ?*/
            if (strpos($contentType->get('mime_type'), 'text/html') !== false) {
                /* Insert Startup jscripts & CSS scripts into template - template must have a </head> tag */
                if (($js= $this->modx->getRegisteredClientStartupScripts()) && (strpos($this->modx->resource->_output, '</head>') !== false)) {
                    /* change to just before closing </head> */
                    $this->modx->resource->_output= preg_replace("/(<\/head>)/i", $js . "\n\\1", $this->modx->resource->_output);
                }

                /* Insert jscripts & html block into template - template must have a </body> tag */
                if ((strpos($this->modx->resource->_output, '</body>') !== false) && ($js= $this->modx->getRegisteredClientScripts())) {
                    $this->modx->resource->_output= preg_replace("/(<\/body>)/i", $js . "\n\\1", $this->modx->resource->_output);
                }
            }

            $this->modx->beforeRender();

            /* invoke OnWebPagePrerender event */
            if (!isset($options['noEvent']) || empty($options['noEvent'])) {
                $this->modx->invokeEvent('OnWebPagePrerender');
            }

            $mtime= microtime();
            $mtime= explode(" ", $mtime);
            $mtime= $mtime[1] + $mtime[0];
            $totalTime= ($mtime - $this->modx->startTime);
            $queries= 0;
            $queryTime= 0;
            if ($this->modx->db !== null && $this->modx->db instanceof DBAPI) {
                $queryTime= $this->modx->queryTime;
                $queryTime= sprintf("%2.4f s", $queryTime);
                $queries= isset ($this->modx->executedQueries) ? $this->modx->executedQueries : 0;
            }
            $totalTime= sprintf("%2.4f s", $totalTime);
            $phpTime= $totalTime - $queryTime;
            $phpTime= sprintf("%2.4f s", $phpTime);
            $source= $this->modx->resourceGenerated ? "database" : "cache";
            $this->modx->resource->_output= str_replace("[^q^]", $queries, $this->modx->resource->_output);
            $this->modx->resource->_output= str_replace("[^qt^]", $queryTime, $this->modx->resource->_output);
            $this->modx->resource->_output= str_replace("[^p^]", $phpTime, $this->modx->resource->_output);
            $this->modx->resource->_output= str_replace("[^t^]", $totalTime, $this->modx->resource->_output);
            $this->modx->resource->_output= str_replace("[^s^]", $source, $this->modx->resource->_output);
        } else {
            $this->modx->beforeRender();

            /* invoke OnWebPagePrerender event */
            if (!isset($options['noEvent']) || empty($options['noEvent'])) {
                $this->modx->invokeEvent("OnWebPagePrerender");
            }
        }

        /* send out content-type, content-disposition, and custom headers from the content type */
        if ($this->modx->context->getOption('set_header')) {
            $type= $contentType->get('mime_type') ? $contentType->get('mime_type') : 'text/html';
            $header= 'Content-Type: ' . $type;
            if (!$contentType->get('binary')) {
                $charset= $this->modx->context->getOption('modx_charset','UTF-8');
                $header .= '; charset=' . $charset;
            }
            header($header);
            if (!$this->modx->checkPreview()) {
                $dispositionSet= false;
                if ($customHeaders= $contentType->get('headers')) {
                    foreach ($customHeaders as $headerKey => $headerString) {
                        header($headerString);
                        if (strpos($headerString, 'Content-Disposition:') !== false) $dispositionSet= true;
                    }
                }
                if (!$dispositionSet && $this->modx->resource->get('content_dispo')) {
                    if ($alias= array_search($this->modx->resourceIdentifier, $this->modx->aliasMap)) {
                        $name= basename($alias);
                    } elseif ($this->modx->resource->get('alias')) {
                        $name= $this->modx->resource->get('alias');
                        if ($ext= $contentType->getExtension()) {
                            $name .= ".{$ext}";
                        }
                    } elseif ($name= $this->modx->resource->get('pagetitle')) {
                        $name= $this->modx->resource->cleanAlias($name);
                        if ($ext= $contentType->getExtension()) {
                            $name .= ".{$ext}";
                        }
                    } else {
                        $name= 'download';
                        if ($ext= $contentType->getExtension()) {
                            $name .= ".{$ext}";
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

        if ($this->modx->resource instanceof modStaticResource && $contentType->get('binary')) {
            $this->modx->resource->process();
        } else {
            if ($contentType->get('binary')) {
                $this->modx->resource->_output = $this->modx->resource->process();
            }
            echo $this->modx->resource->_output;
            while (@ ob_end_flush()) {}
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
     * @param integer $count_attempts The number of times to attempt redirection.
     * @param string $type The type of redirection to attempt.
     */
    public function sendRedirect($url, $count_attempts= 0, $type= '', $responseCode= '') {
        if (empty ($url)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Attempted to redirect to an empty URL.");
            return false;
        }
        if (!$this->modx->getRequest()) {
            $this->modx->log(modX::LOG_LEVEL_FATAL, "Could not load request class.");
        }
        $this->modx->request->preserveRequest('referrer.redirected');
        if ($count_attempts == 1) {
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
        if ($type == 'REDIRECT_REFRESH') {
            $header= 'Refresh: 0;URL=' . $url;
        }
        elseif ($type == 'REDIRECT_META') {
            $header= '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=' . $url . '" />';
            echo $header;
            exit();
        }
        else {
            if (strpos($url, '://') === false && !(substr($url, 0, 1) === '/' || substr($url, 0, 2) === './' || substr($url, 0, 3) === '../')) {
                $url= $this->modx->context->getOption('site_url','/') . $url;
            }
            $header= 'Location: ' . $url;
        }
        if ($responseCode && (strpos($responseCode, '30') !== false)) {
            header($responseCode);
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
