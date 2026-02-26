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
 * [[~web]] Creates a URL to the site_start resource of the context (e.g. web).
 *
 * @package MODX\Revolution
 */
class modLinkTag extends modTag
{
    /**
     * Overrides modTag::__construct to set the Link Tag token.
     * {@inheritdoc}
     */
    public function __construct(modX &$modx)
    {
        parent::__construct($modx);
        $this->setToken('~');
    }

    /**
     * Processes the modLinkTag, recursively processing nested tags.
     *
     * {@inheritdoc}
     */
    public function process($properties = null, $content = null)
    {
        parent::process($properties, $content);
        if (!$this->_processed) {
            $this->_output = $this->_content;
            if (is_string($this->_output) && !empty($this->_output)) {
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
                $context = $this->resolveContextLinkTarget();
                if ($this->modx->getOption('friendly_urls', null, false) && $context === '' && !empty($this->_output)) {
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
                                $scheme = (int)$scheme;
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

        return $this->_output;
    }

    /**
     * Resolves [[~contextKey]] to site_start resource id and returns the context key.
     * If link target is numeric or empty, leaves _output unchanged and returns ''.
     *
     * @return string Context key or empty string
     */
    private function resolveContextLinkTarget()
    {
        $linkTarget = $this->_output;
        if (is_numeric($linkTarget) || $linkTarget === '') {
            return '';
        }
        $ctx = $this->modx->getContext($linkTarget);
        if (!$ctx instanceof modContext) {
            $this->modx->log(
                modX::LOG_LEVEL_WARN,
                'Link tag unknown context: `' . $this->_tag . '`'
            );
            $this->_output = '';
            return '';
        }
        $siteStart = $ctx->getOption('site_start');
        if ($siteStart !== null && $siteStart !== '') {
            $this->_output = (string) $siteStart;
            return $linkTarget;
        }
        $this->modx->log(
            modX::LOG_LEVEL_WARN,
            'Link tag context has no site_start: `' . $this->_tag . '`'
        );
        $this->_output = '';
        return '';
    }
}
