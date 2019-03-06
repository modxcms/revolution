<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution;

/**
 * Represents link tags.
 *
 * [[~12]] Creates a URL from the specified resource identifier.
 *
 * @package MODX\Revolution
 */
class modLinkTag extends modTag
{
    /**
     * Overrides modTag::__construct to set the Link Tag token
     * {@inheritdoc}
     */
    function __constructor(modX & $modx)
    {
        parent:: __construct($modx);
        $this->setToken('~');
    }

    /**
     * Processes the modLinkTag, recursively processing nested tags.
     *
     * {@inheritdoc}
     */
    public function process($properties = null, $content = null)
    {
        parent:: process($properties, $content);
        if (!$this->_processed) {
            $this->_output = $this->_content;
            if (is_string($this->_output) && !empty ($this->_output)) {
                /* collect element tags in the content and process them */
                $maxIterations = intval($this->modx->getOption('parser_max_iterations', null, 10));
                $this->modx->parser->processElementTags(
                    $this->_tag,
                    $this->_output,
                    $this->modx->parser->isProcessingUncacheable(),
                    $this->modx->parser->isRemovingUnprocessed(),
                    '[[',
                    ']]',
                    [],
                    $maxIterations
                );
                $context = '';
                if ($this->modx->getOption('friendly_urls', null, false)) {
                    if (array_key_exists('context', $this->_properties)) {
                        $context = $this->_properties['context'];
                    }
                    if ($context) {
                        $resource = $this->modx->findResource($this->_output, $context);
                        if ($resource) {
                            $this->_output = $resource;
                        }
                    }
                }
                if (!empty($this->_output)) {
                    $qs = '';
                    $scheme = $this->modx->getOption('link_tag_scheme', null, -1);
                    $options = [];
                    if (is_array($this->_properties) && !empty($this->_properties)) {
                        $qs = [];
                        if (array_key_exists('context', $this->_properties)) {
                            $context = $this->_properties['context'];
                            unset($this->_properties['context']);
                        }
                        if (array_key_exists('scheme', $this->_properties)) {
                            $scheme = $this->_properties['scheme'];
                            unset($this->_properties['scheme']);
                            if (is_numeric($scheme)) {
                                $scheme = (integer)$scheme;
                            }
                        }
                        if (array_key_exists('use_weblink_target', $this->_properties)) {
                            $options['use_weblink_target'] = $this->_properties['use_weblink_target'];
                            unset($this->_properties['use_weblink_target']);
                        }
                        foreach ($this->_properties as $propertyKey => $propertyValue) {
                            if (in_array($propertyKey, ['context', 'scheme', 'use_weblink_target'])) {
                                continue;
                            }
                            $qs[] = "{$propertyKey}={$propertyValue}";
                        }
                        if ($qs = implode('&', $qs)) {
                            $qs = rawurlencode($qs);
                            $qs = str_replace(['%26', '%3D'], ['&amp;', '='], $qs);
                        }
                    }
                    $this->_output = $this->modx->makeUrl($this->_output, $context, $qs, $scheme, $options);
                }
            }
            if (!empty($this->_output)) {
                $this->filterOutput();
                $this->cache();
                $this->_processed = true;
            }
            if (empty($this->_output)) {
                $this->modx->log(
                    modX::LOG_LEVEL_ERROR,
                    'Bad link tag `' . $this->_tag . '` encountered',
                    '',
                    $this->modx->resource
                        ? "resource {$this->modx->resource->id}"
                        : ($_SERVER['REQUEST_URI'] ? "uri {$_SERVER['REQUEST_URI']}" : '')
                );
            }
        }

        /* finally, return the processed element content */

        return $this->_output;
    }
}
