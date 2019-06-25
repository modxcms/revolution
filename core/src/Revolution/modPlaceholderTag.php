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
 * Represents placeholder tags.
 *
 * [[+placeholder_key]] Represents a placeholder with name placeholder_key.
 *
 * @uses    modX::getPlaceholder() To retrieve the placeholder value.
 *
 * @package MODX\Revolution
 */
class modPlaceholderTag extends modTag
{
    /**
     * Overrides modTag::__construct to set the Placeholder Tag token
     * {@inheritdoc}
     */
    function __construct(modX & $modx)
    {
        parent:: __construct($modx);
        $this->setCacheable(false);
        $this->setToken('+');
    }

    /**
     * Processes the modPlaceholderTag, recursively processing nested tags.
     *
     * Tags in the properties of the tag itself, or the content returned by the
     * tag element are processed.  Non-cacheable nested tags are only processed
     * if this tag element is also non-cacheable.
     *
     * {@inheritdoc}
     */
    public function process($properties = null, $content = null)
    {
        parent:: process($properties, $content);
        if (!$this->_processed) {
            $this->_output = $this->_content;
            if ($this->_output !== null && is_string($this->_output) && !empty($this->_output)) {
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
            }
            if ($this->_output !== null || $this->modx->parser->startedProcessingUncacheable()) {
                $this->filterOutput();
                $this->_processed = true;
            }
        }

        /* finally, return the processed element content */

        return $this->_output;
    }

    /**
     * Get the raw source content of the field.
     *
     * {@inheritdoc}
     */
    public function getContent(array $options = [])
    {
        if (!is_string($this->_content)) {
            if (isset($options['content'])) {
                $this->_content = $options['content'];
            } else {
                $this->_content = $this->modx->getPlaceholder($this->get('name'));
            }
        }

        return $this->_content;
    }

    /**
     * modPlaceholderTag instances cannot be cacheable.
     *
     * @return boolean Always returns false.
     */
    public function isCacheable()
    {
        return false;
    }

    /**
     * modPlaceholderTag instances cannot be cacheable.
     *
     * {@inheritdoc}
     */
    public function setCacheable($cacheable = true)
    {
    }
}
